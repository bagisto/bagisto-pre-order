<?php

namespace Webkul\PreOrder\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    public function run()
    {
        $preorderAttributes = DB::table('attributes')->select('id')
                                                ->where('code','allow_preorder')
                                                ->orWhere('code', 'preorder_qty')
                                                ->orWhere('code', 'preorder_availability')
                                                ->get();
                                    
        foreach($preorderAttributes as $preorderAttributeData)
        {
            DB::table('attribute_group_mappings')->where('attribute_id',$preorderAttributeData->id)->delete();
            DB::table('attributes')->where('id',$preorderAttributeData->id)->delete();
        }
      
        $allowPreorderAttribute = app('Webkul\Attribute\Repositories\AttributeRepository')->create([
            "code"                  => "allow_preorder",
            "type"                  => "boolean",
            "admin_name"            => "Allow Preorder",
            "is_required"           => 0,
            "is_unique"             => 0,
            "validation"            => "",
            "value_per_locale"      => 0,
            "value_per_channel"     => 1,
            "is_filterable"         => 0,
            "is_configurable"       => 0,
            "is_visible_on_front"   => 0,
            "is_user_defined"       => 1,
            "use_in_flat"           => 1
        ]);

        $preorderQtyAttribute = app('Webkul\Attribute\Repositories\AttributeRepository')->create([
            "code"                  => "preorder_qty",
            "type"                  => "text",
            "admin_name"            => "Preorder Qty",
            "is_required"           => 0,
            "is_unique"             => 0,
            "validation"            => "numeric",
            "value_per_locale"      => 0,
            "value_per_channel"     => 1,
            "is_filterable"         => 0,
            "is_configurable"       => 0,
            "is_visible_on_front"   => 0,
            "is_user_defined"       => 1,
            "use_in_flat"           => 1
        ]);

        $preorderAvailabilityAttribute = app('Webkul\Attribute\Repositories\AttributeRepository')->create([
            "code"                  => "preorder_availability",
            "type"                  => "date",
            "admin_name"            => "Product Availability",
            "is_required"           => 0,
            "is_unique"             => 0,
            "validation"            => "",
            "value_per_locale"      => 0,
            "value_per_channel"     => 1,
            "is_filterable"         => 0,
            "is_configurable"       => 0,
            "is_visible_on_front"   => 0,
            "is_user_defined"       => 1,
            "use_in_flat"           => 1
        ]);

        $attributeFamilies = app('Webkul\Attribute\Repositories\AttributeFamilyRepository')->all();

        foreach ($attributeFamilies as $attributeFamily) {
            $generalGroup = $attributeFamily->attribute_groups()->where('name', 'General')->first();

            $generalGroup->custom_attributes()->save($allowPreorderAttribute, [ 'position' => $generalGroup->custom_attributes()->count() + 1 ]);

            $generalGroup->custom_attributes()->save($preorderQtyAttribute, [ 'position' => $generalGroup->custom_attributes()->count() + 2 ]);

            $generalGroup->custom_attributes()->save($preorderAvailabilityAttribute, [ 'position' => $generalGroup->custom_attributes()->count() + 3 ]);
        }
    }
}