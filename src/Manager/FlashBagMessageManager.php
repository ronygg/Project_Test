<?php

namespace App\Manager;

use Symfony\Contracts\Translation\TranslatorInterface;

readonly class FlashBagMessageManager
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function createFlashMessage(
        mixed $message,
        string $type = '',
        string $errorDetail = ''
    ): string {
        return $this->translateMessage($message, $errorDetail);
    }

    public function translateMessage(
        $message,
        string $errorDetail = ''
    ): string {
        $translator = $this->translator;

        if (!empty($errorDetail)) {
            return $translator->trans($message,
                ['%error_detail%' => $errorDetail]
            );
        }

        return $translator->trans($message);
    }
}
