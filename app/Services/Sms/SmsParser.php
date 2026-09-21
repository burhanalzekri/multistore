<?php
namespace App\Services\Sms;

use App\Models\SmsPattern;

class SmsParser {
    public function parse(string $body): ?array {
        $text = $this->normalize($body);
        foreach (SmsPattern::where('is_active', true)->get() as $p) {
            $ok = true;
            foreach (($p->keywords ?? []) as $kw) {
                if (!str_contains($text, $this->normalize($kw))) { $ok = false; break; }
            }
            if (!$ok) continue;
            $amount = $this->grab($p->amount_regex, $text);
            $sender = $this->grab($p->sender_regex, $text);
            $ref = $p->reference_regex ? $this->grab($p->reference_regex, $text) : null;
            if ($amount && $sender) {
                return [
                    'provider'  => $p->provider,
                    'amount'    => (float) str_replace(',', '', $amount),
                    'sender'    => $sender,
                    'reference' => $ref,
                ];
            }
        }
        return null;
    }
    private function grab(string $re, string $t): ?string {
        return preg_match($re, $t, $m) ? trim($m[1]) : null;
    }
    private function normalize(string $t): string {
        $t = str_replace(['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'], ['0','1','2','3','4','5','6','7','8','9'], $t);
        return trim(preg_replace('/\s+/u', ' ', $t));
    }
}
