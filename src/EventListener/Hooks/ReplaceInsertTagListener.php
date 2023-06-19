<?php

namespace Plenta\ProtectedSelect\EventListener\Hooks;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\FormFieldModel;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsHook('replaceInsertTags', method: 'onReplaceInsertTags')]
class ReplaceInsertTagListener
{
    public const TAG = 'protected_select';

    public function __construct(protected RequestStack $requestStack)
    {
    }

    public function onReplaceInsertTags(string $insertTag)
    {
        $chunks = explode('::', $insertTag);

        if ($chunks[0] !== self::TAG) {
            return false;
        } else {
            $formFields = FormFieldModel::findBy(['type = ?', 'name = ?'], ['protectedselect', $chunks[1]]);
            $request = $this->requestStack->getCurrentRequest();
            foreach ($formFields as $formField) {
                if (!str_contains($request->get('FORM_SUBMIT', ''), $formField->pid)) {
                    continue;
                }
                $options = StringUtil::deserialize($formField->protectedOptions);
                $ref = $request->get($chunks[1]);
                foreach ($options as $option) {
                    if ($option['reference'] === $ref) {
                        return match ($chunks[2] ?? null) {
                            'reference' => $ref,
                            'label' => $option['label'],
                            default => $option['value']
                        };
                    }
                }
            }
        }

        return false;
    }
}