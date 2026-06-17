<?php

declare(strict_types=1);

/**
 * Plenta Protected Select Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2015-2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       http://opensource.org/licenses/lgpl-3.0.html
 * @link          https://github.com/plenta/
 */

namespace Plenta\ProtectedSelect\Tests\Classes;

use PHPUnit\Framework\TestCase;
use Plenta\ProtectedSelect\Classes\FormProtectedSelectMenu;

/**
 * Regression tests for issue #25: server-side validation must accept an array
 * of references when the field runs in multiple-selection mode.
 *
 * @see https://github.com/christianbarkowsky/contao-protectedselect/issues/25
 */
class FormProtectedSelectMenuTest extends TestCase
{
    private function createWidget(): FormProtectedSelectMenu
    {
        // The Contao Widget constructor boots framework dependencies we do not
        // need here, so we bypass it with an anonymous subclass that exposes the
        // protected validation method and lets us seed the protected options.
        $widget = new class() extends FormProtectedSelectMenu {
            public function __construct()
            {
                // Intentionally skip parent::__construct().
            }

            public function isValidOptionPublic($varInput): bool
            {
                return $this->isValidOption($varInput);
            }
        };

        // Stored exactly as Contao persists the protectedOptionWizard blob.
        $widget->protectedOptions = serialize([
            ['value' => 'team@example.com', 'reference' => 'ref-team'],
            ['value' => 'sales@example.com', 'reference' => 'ref-sales'],
        ]);

        return $widget;
    }

    public function testSingleValidReferenceIsAccepted(): void
    {
        self::assertTrue($this->createWidget()->isValidOptionPublic('ref-team'));
    }

    public function testSingleInvalidReferenceIsRejected(): void
    {
        self::assertFalse($this->createWidget()->isValidOptionPublic('not-a-reference'));
    }

    public function testMultipleValidReferencesAreAccepted(): void
    {
        self::assertTrue($this->createWidget()->isValidOptionPublic(['ref-team', 'ref-sales']));
    }

    public function testMultipleWithOneInvalidReferenceIsRejected(): void
    {
        self::assertFalse($this->createWidget()->isValidOptionPublic(['ref-team', 'not-a-reference']));
    }

    public function testSingleValidReferenceInArrayIsAccepted(): void
    {
        self::assertTrue($this->createWidget()->isValidOptionPublic(['ref-sales']));
    }
}
