<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $initialSpecs = [
            [
                'name' => 'steel_grades',
                'display_name' => 'Steel Grades',
                'type' => 'select',
                'is_filterable' => true,
                'is_required' => false,
                'sort_order' => 1,
                'status' => 'active',
                'values' => ['Fe 500D', 'Fe 550D', 'Fe 600', 'CRS Grade']
            ],
            [
                'name' => 'cement_types',
                'display_name' => 'Cement Types',
                'type' => 'select',
                'is_filterable' => true,
                'is_required' => false,
                'sort_order' => 2,
                'status' => 'active',
                'values' => ['OPC 43', 'OPC 53', 'PPC', 'PSC', 'White Cement']
            ],
            [
                'name' => 'brick_block_sizes',
                'display_name' => 'Brick & Block Sizes',
                'type' => 'select',
                'is_filterable' => true,
                'is_required' => false,
                'sort_order' => 3,
                'status' => 'active',
                'values' => ['Standard 9×4×3 inch', 'AAC Block 600×200×150mm', 'Fly Ash 230×110×75mm', 'Solid Concrete Block']
            ],
            [
                'name' => 'plumbing_pipe_diameters',
                'display_name' => 'Plumbing Pipe Diameters',
                'type' => 'select',
                'is_filterable' => true,
                'is_required' => false,
                'sort_order' => 4,
                'status' => 'active',
                'values' => ['20mm (1/2 inch)', '25mm (3/4 inch)', '32mm (1 inch)', '40mm (1-1/4 inch)', '50mm (2 inch)']
            ],
            [
                'name' => 'electrical_cable_sizes',
                'display_name' => 'Electrical Cable Sizes',
                'type' => 'select',
                'is_filterable' => true,
                'is_required' => false,
                'sort_order' => 5,
                'status' => 'active',
                'values' => ['1.5 sq mm', '2.5 sq mm', '4.0 sq mm', '6.0 sq mm', 'Armoured Cable 10 sq mm']
            ]
        ];

        foreach ($initialSpecs as $spec) {
            $attribute = Attribute::updateOrCreate(
                ['name' => $spec['name']],
                [
                    'display_name'  => $spec['display_name'],
                    'type'          => $spec['type'],
                    'is_filterable' => $spec['is_filterable'],
                    'is_required'   => $spec['is_required'],
                    'sort_order'    => $spec['sort_order'],
                    'status'        => $spec['status'],
                ]
            );

            foreach ($spec['values'] as $idx => $val) {
                AttributeValue::updateOrCreate(
                    [
                        'attribute_id' => $attribute->id,
                        'value'        => $val,
                    ],
                    [
                        'display_value' => $val,
                        'sort_order'    => $idx,
                        'status'        => 'active',
                    ]
                );
            }
        }
    }
}
