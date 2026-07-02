<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Notifiable;

    protected $table = 'tbl_users';
    protected $primaryKey = 'userId';
    protected $fillable = [
        'fullName',
        'username',
        'email',
        'password',
        'avatar',
        'phoneNumber',
        'address',
        'ipAdress',
        'isActive',
        'status',
    ];
    const CREATED_AT = 'createdDate';
    const UPDATED_AT = 'updatedDate';
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function getUserId($username)
    {
        return DB::table($this->table)
            ->select('userId')
            ->where('username', $username)->value('userId');
    }
    public function getUser($id)
    {
        $users = DB::table($this->table)
            ->where('userId', $id)->first();

        return $users;
    }

    public function updateUser($id, $data)
    {
        $update = DB::table($this->table)
            ->where('userid', $id)
            ->update($data);

        return $update;
    }

    public function getMyTours($id)
    {
        $myTours =  DB::table('tbl_booking')
        ->join('tbl_tours', 'tbl_booking.tourId', '=', 'tbl_tours.tourId')
        ->join('tbl_checkout', 'tbl_booking.bookingId', '=', 'tbl_checkout.bookingId')
        ->where('tbl_booking.userId', $id)
        ->orderByDesc('tbl_booking.bookingDate')
        ->take(3)
        ->get();

        foreach ($myTours as $tour) {
            // Lấy rating từ tbl_reviews cho mỗi tour
            $tour->rating = DB::table('tbl_reviews')
                ->where('tourId', $tour->tourId)
                ->where('userId', $id)
                ->value('rating'); // Dùng value() để lấy giá trị rating
        }
        foreach ($myTours as $tour) {
            $tour->rating = DB::table('tbl_reviews')
                ->where('tourId', $tour->tourId)
                ->where('userId', $id)
                ->value('rating') ?? 0; 
            
            $tour->images = DB::table('tbl_images')
                ->where('tourId', $tour->tourId)
                ->pluck('imageUrl');
        }

        return $myTours;
    }
    
}
