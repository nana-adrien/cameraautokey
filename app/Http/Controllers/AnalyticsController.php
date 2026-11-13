<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsEvent;
use App\Models\AnalyticsPageView;
use App\Models\AnalyticsVisit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnalyticsController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $data = $request->validate([
            'visit' => ['required', 'uuid'],
            'event' => ['required', 'in:page_leave,form_start,form_step,form_submit,form_abandon'],
            'page_view' => ['nullable', 'integer', 'min:1'],
            'duration_ms' => ['nullable', 'integer', 'min:0', 'max:3600000'],
            'step' => ['nullable', 'integer', 'min:1', 'max:3'],
            'form' => ['nullable', 'string', 'max:40'],
        ]);

        $visit = AnalyticsVisit::where('uuid', $data['visit'])->latest('last_seen_at')->first();

        if (! $visit) {
            return response()->noContent();
        }

        $visit->update(['last_seen_at' => now()]);

        if (isset($data['page_view'], $data['duration_ms'])) {
            $pageView = AnalyticsPageView::where('id', $data['page_view'])
                ->where('visit_id', $visit->id)
                ->first();

            if ($pageView && ($pageView->duration_ms === null || $data['duration_ms'] > $pageView->duration_ms)) {
                $pageView->update(['duration_ms' => $data['duration_ms']]);
            }
        }

        if ($data['event'] === 'page_leave') {
            return response()->noContent();
        }

        AnalyticsEvent::create([
            'visit_id' => $visit->id,
            'name' => $data['event'],
            'step' => $data['step'] ?? null,
            'payload' => array_filter([
                'form' => $data['form'] ?? null,
                'step' => $data['step'] ?? null,
            ], fn ($value) => $value !== null),
            'occurred_at' => now(),
        ]);

        $updates = [];

        if ($data['event'] === 'form_step') {
            $updates['last_step'] = max((int) $visit->last_step, (int) ($data['step'] ?? 0)) ?: null;
        }

        if ($data['event'] === 'form_submit') {
            $updates['converted'] = true;
            $updates['converted_at'] = now();

            if (isset($data['step'])) {
                $updates['last_step'] = $data['step'];
            }
        }

        if ($data['event'] === 'form_abandon' && isset($data['step'])) {
            $updates['last_step'] = max((int) $visit->last_step, (int) $data['step']);
        }

        if ($updates !== []) {
            $visit->update($updates);
        }

        return response()->noContent();
    }
}
