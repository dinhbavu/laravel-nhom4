<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    protected $table = 'thong_bao';
    public $timestamps = false;

    protected $fillable = [
        'nguoi_nhan_id', 'tieu_de', 'noi_dung', 'loai', 'da_doc', 'duong_dan',
    ];

    protected $casts = ['da_doc' => 'boolean'];

    public function nguoiNhan()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_nhan_id');
    }
}
