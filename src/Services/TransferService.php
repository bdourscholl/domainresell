<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transfer;
use App\Services\Registrar\RegistrarFactory;

class TransferService
{
    public function initiateTransfer(int $userId, string $domain, string $tld, string $eppCode, string $registrar = 'namecheap'): array
    {
        $transferId = Transfer::create([
            'user_id' => $userId,
            'domain_name' => $domain . $tld,
            'tld' => $tld,
            'epp_code' => $eppCode,
            'registrar' => $registrar,
            'status' => 'pending',
        ]);

        return ['success' => true, 'transfer_id' => $transferId];
    }

    public function processTransfer(int $transferId): array
    {
        $transfer = Transfer::find($transferId);
        if (!$transfer) {
            return ['success' => false, 'error' => 'Transfer not found'];
        }

        $parts = explode('.', $transfer['domain_name'], 2);
        $sld = $parts[0];
        $tld = '.' . ($parts[1] ?? '');

        $registrar = RegistrarFactory::create($transfer['registrar']);
        $result = $registrar->transferDomain($sld, $tld, $transfer['epp_code'], []);

        if ($result['success']) {
            Transfer::update($transferId, [
                'status' => 'processing',
                'registrar_transfer_id' => $result['transfer_id'] ?? '',
            ]);
        } else {
            Transfer::update($transferId, [
                'status' => 'failed',
                'error_message' => $result['error'] ?? 'Unknown error',
            ]);
        }

        return $result;
    }
}
