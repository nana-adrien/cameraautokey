<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\Order;
use App\Support\Money;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TelegramNotifier
{
    public function sendOrder(Order $order): void
    {
        if (! $this->configured() || DemoFeatures::isLocked('telegram')) {
            return;
        }

        $caption = $this->orderCaption($order);

        try {
            $pdf = app(OrderPdf::class)->render($order);
            $this->sendDocument($pdf, "demande-{$order->reference}.pdf", $caption, 'admin.orders.show', $order);
        } catch (\Throwable $e) {
            Log::warning('Telegram: envoi de la demande échoué', [
                'reference' => $order->reference,
                'error' => $e->getMessage(),
            ]);

            $this->sendText($caption, 'admin.orders.show', $order);
        }
    }

    public function sendContactMessage(ContactMessage $message): void
    {
        if (! $this->configured() || DemoFeatures::isLocked('telegram') || $message->type === 'newsletter') {
            return;
        }

        $caption = $this->messageCaption($message);

        try {
            $pdf = app(ContactMessagePdf::class)->render($message);
            $this->sendDocument($pdf, "message-{$message->id}.pdf", $caption, 'admin.messages.show', $message);
        } catch (\Throwable $e) {
            Log::warning('Telegram: envoi du message échoué', [
                'message_id' => $message->id,
                'error' => $e->getMessage(),
            ]);

            $this->sendText($caption, 'admin.messages.show', $message);
        }
    }

    public function sendJobApplication(JobApplication $application): void
    {
        if (! $this->configured() || DemoFeatures::isLocked('telegram')) {
            return;
        }

        $caption = $this->applicationCaption($application);

        try {
            $this->sendDocument(Storage::get($application->cv_path), $application->cv_original_name, $caption, 'admin.applications.show', $application);
        } catch (\Throwable $e) {
            Log::warning('Telegram: envoi de la candidature échoué', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);

            $this->sendText($caption, 'admin.applications.show', $application);
        }
    }

    private function sendDocument(string $pdf, string $filename, string $caption, string $route, object $model): void
    {
        $response = Http::timeout(15)
            ->attach('document', $pdf, $filename)
            ->post($this->url('sendDocument'), [
                'chat_id' => $this->chatId(),
                'caption' => Str::limit($caption, 1000, '…'),
                'parse_mode' => 'HTML',
                'reply_markup' => $this->keyboard($route, $model),
            ]);

        if ($response->failed()) {
            throw new \RuntimeException($response->body());
        }
    }

    private function sendText(string $text, string $route, object $model): void
    {
        try {
            $response = Http::timeout(8)->post($this->url('sendMessage'), [
                'chat_id' => $this->chatId(),
                'text' => Str::limit($text, 3900, '…'),
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
                'reply_markup' => $this->keyboard($route, $model),
            ]);

            if ($response->failed()) {
                throw new \RuntimeException($response->body());
            }
        } catch (\Throwable $e) {
            Log::warning('Telegram: notification texte échouée', ['error' => $e->getMessage()]);
        }
    }

    private function orderCaption(Order $order): string
    {
        return implode("\n", [
            '🔑 <b>Nouvelle demande</b> <code>'.$this->escape($order->reference).'</code>',
            '🚗 '.$this->escape($order->brand_model).' ('.$order->year.') — '.$this->escape($order->plate),
            '🛠 '.$this->escape($order->label('need')).' · '.$this->escape($order->label('key_type')).' × '.$order->quantity,
            '📍 '.$this->escape($order->address).', '.$this->escape($order->district).', '.$this->escape($order->city),
            '👤 '.$this->escape($order->customer_name).' — '.$this->escape($order->customer_phone),
            '💰 '.$this->escape(Money::fcfa($order->estimated_price)).($order->emergency ? ' · ⚡ Express' : ''),
        ]);
    }

    private function messageCaption(ContactMessage $message): string
    {
        $lines = [
            '✉️ <b>Nouveau message</b> — '.$this->escape($message->typeLabel()),
            '👤 '.$this->escape($message->name).' — '.$this->escape($message->email),
        ];

        if ($message->phone) {
            $lines[] = '📞 '.$this->escape($message->phone);
        }

        if ($message->company) {
            $lines[] = '🏢 '.$this->escape($message->company);
        }

        if ($message->subject) {
            $lines[] = '📝 '.$this->escape($message->subject);
        }

        $lines[] = '';
        $lines[] = $this->escape(Str::limit($message->message, 600, '…'));

        return implode("\n", $lines);
    }

    private function applicationCaption(JobApplication $application): string
    {
        $offer = $application->jobOffer;

        $lines = [
            '💼 <b>Nouvelle candidature</b> — '.$this->escape($offer->title),
            '🏢 '.$this->escape($offer->companyLabel()).' · '.$this->escape($offer->location),
            '👤 '.$this->escape($application->name).' — '.$this->escape($application->phone),
            '✉️ '.$this->escape($application->email),
        ];

        if ($application->message) {
            $lines[] = '';
            $lines[] = $this->escape(Str::limit($application->message, 500, '…'));
        }

        return implode("\n", $lines);
    }

    private function keyboard(string $route, object $model): string
    {
        return json_encode([
            'inline_keyboard' => [[
                ['text' => 'Ouvrir dans l’admin', 'url' => route($route, $model)],
            ]],
        ], JSON_UNESCAPED_UNICODE);
    }

    private function url(string $method): string
    {
        return 'https://api.telegram.org/bot'.$this->token().'/'.$method;
    }

    private function configured(): bool
    {
        return $this->token() !== '' && $this->chatId() !== '';
    }

    private function token(): string
    {
        return (string) config('services.telegram.bot_token');
    }

    private function chatId(): string
    {
        return (string) config('services.telegram.chat_id');
    }

    private function escape(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
