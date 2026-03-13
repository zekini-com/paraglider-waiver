<?php

namespace Tests\Unit\Enums;

use App\Enums\EmergencyContactRelationship;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class EmergencyContactRelationshipTest extends TestCase
{
    #[Test]
    public function it_has_all_expected_cases(): void
    {
        $cases = EmergencyContactRelationship::cases();

        $this->assertCount(6, $cases);

        $values = array_map(fn ($case) => $case->value, $cases);

        $this->assertContains('Spouse', $values);
        $this->assertContains('Parent', $values);
        $this->assertContains('Sibling', $values);
        $this->assertContains('Child', $values);
        $this->assertContains('Friend', $values);
        $this->assertContains('Other', $values);
    }

    #[Test]
    public function label_returns_the_value_for_each_case(): void
    {
        foreach (EmergencyContactRelationship::cases() as $case) {
            $this->assertSame($case->value, $case->label());
        }
    }

    #[Test]
    public function it_can_be_created_from_value(): void
    {
        $relationship = EmergencyContactRelationship::from('Spouse');

        $this->assertSame(EmergencyContactRelationship::Spouse, $relationship);
    }

    #[Test]
    public function try_from_returns_null_for_invalid_value(): void
    {
        $result = EmergencyContactRelationship::tryFrom('InvalidValue');

        $this->assertNull($result);
    }
}
