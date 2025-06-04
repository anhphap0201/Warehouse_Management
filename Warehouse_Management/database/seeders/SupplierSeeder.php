<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Công ty TNHH Thương mại ABC',
                'contact_person' => 'Nguyễn Văn A',
                'email' => 'contact@abc-trading.com',
                'phone' => '0901234567',
                'address' => '123 Đường Lê Lợi, Quận 1, TP. Hồ Chí Minh',
                'tax_number' => '0123456789',
                'description' => 'Chuyên cung cấp thiết bị điện tử và linh kiện máy tính',
                'status' => 'active'
            ],
            [
                'name' => 'Nhà phân phối XYZ',
                'contact_person' => 'Trần Thị B',
                'email' => 'info@xyz-distributor.vn',
                'phone' => '0987654321',
                'address' => '456 Đường Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh',
                'tax_number' => '0987654321',
                'description' => 'Phân phối các sản phẩm văn phòng phẩm và thiết bị in ấn',
                'status' => 'active'
            ],
            [
                'name' => 'Tập đoàn DEF Industries',
                'contact_person' => 'Lê Văn C',
                'email' => 'sales@def-industries.com',
                'phone' => '0369258147',
                'address' => '789 Đường Điện Biên Phủ, Quận Bình Thạnh, TP. Hồ Chí Minh',
                'tax_number' => '0369258147',
                'description' => 'Sản xuất và cung cấp vật liệu xây dựng, công cụ cầm tay',
                'status' => 'active'
            ],
            [
                'name' => 'Công ty Cổ phần Thực phẩm GHI',
                'contact_person' => 'Phạm Thị D',
                'email' => 'order@ghi-food.vn',
                'phone' => '0147258369',
                'address' => '321 Đường Cách Mạng Tháng 8, Quận 10, TP. Hồ Chí Minh',
                'tax_number' => '0147258369',
                'description' => 'Cung cấp thực phẩm đóng gói và đồ uống',
                'status' => 'active'
            ],
            [
                'name' => 'Nhà cung cấp JKL Tech',
                'contact_person' => 'Võ Văn E',
                'email' => 'support@jkl-tech.com',
                'phone' => '0258147369',
                'address' => '654 Đường Võ Văn Tần, Quận 3, TP. Hồ Chí Minh',
                'tax_number' => '0258147369',
                'description' => 'Chuyên về thiết bị công nghệ thông tin và phần mềm',
                'status' => 'inactive'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
