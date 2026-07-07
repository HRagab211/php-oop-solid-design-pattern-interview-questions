<?php

declare(strict_types=1);

namespace DesignPatterns\Behavioral;

final readonly class Customer
{
    public function __construct(public int $ordersCount, public int $lifetimeValueCents, public bool $emailVerified) {}
}

interface CustomerSpecification
{
    public function isSatisfiedBy(Customer $customer): bool;
}

final class VerifiedCustomerSpecification implements CustomerSpecification
{
    public function isSatisfiedBy(Customer $customer): bool
    {
        return $customer->emailVerified;
    }
}

final class VipCustomerSpecification implements CustomerSpecification
{
    public function isSatisfiedBy(Customer $customer): bool
    {
        return $customer->ordersCount >= 5 && $customer->lifetimeValueCents >= 50000;
    }
}

final readonly class AndCustomerSpecification implements CustomerSpecification
{
    public function __construct(private CustomerSpecification $left, private CustomerSpecification $right) {}

    public function isSatisfiedBy(Customer $customer): bool
    {
        return $this->left->isSatisfiedBy($customer) && $this->right->isSatisfiedBy($customer);
    }
}

final class Specification
{
    public static function run(): array
    {
        $spec = new AndCustomerSpecification(new VerifiedCustomerSpecification(), new VipCustomerSpecification());
        return [$spec->isSatisfiedBy(new Customer(8, 75000, true)) ? 'Customer is VIP.' : 'Customer is not VIP.'];
    }
}
