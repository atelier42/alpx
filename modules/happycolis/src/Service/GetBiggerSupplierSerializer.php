<?php

/**
 * Class GetbiggerSupplierSerializer
 */
class GetBiggerSupplierSerializer
{
    /**
     * @param \Supplier $supplier
     */
    public static function serialize($supplier)
    {
        if ($supplier instanceof \Supplier) {
            if (class_exists('SupplierAddress')) {
                $addressId = \SupplierAddress::getAddressIdBySupplierId($supplier->id);

                if ($addressId) {
                    $supplier->address = new \SupplierAddress($addressId);
                }
            }

            if (class_exists('Address')) {
                $addressId = \Address::getAddressIdBySupplierId($supplier->id);

                if ($addressId) {
                    $supplier->address = new \Address($addressId);
                }
            }
        }

        return $supplier;
    }
}
