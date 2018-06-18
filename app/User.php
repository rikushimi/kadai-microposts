<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
     public function favoriter()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'u_id', 'fav_id')->withTimestamps();
    }
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function follow($userId)
{
    // confirm if already following
    $exist = $this->is_following($userId);
    // confirming that it is not you
    $its_me = $this->id == $userId;

    if ($exist || $its_me) {
        // do nothing if already following
        return false;
    } else {
        // follow if not following
        $this->followings()->attach($userId);
        return true;
    }
}

public function unfollow($userId)
{
    // confirming if already following
    $exist = $this->is_following($userId);
    // confirming that it is not you
    $its_me = $this->id == $userId;


    if ($exist && !$its_me) {
        // stop following if following
        $this->followings()->detach($userId);
        return true;
    } else {
        // do nothing if not following
        return false;
    }
}


public function is_following($userId) 
   {
    return $this->followings()->where('follow_id', $userId)->exists();
    }

     public function feed_microposts()
    {
        $follow_user_ids = $this->followings()-> pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    
    public function favorite($favId)
{
    // confirm if already following
    $exist = $this->is_favorite($favId);

    if ($exist) {
        // do nothing if already following
        return false;
    } else {
        // follow if not following
        $this->favoriter()->attach($favId);
        return true;
    }
}

public function unfavorite($favId)
{
    // confirming if already following
    $exist = $this->is_favorite($favId);



    if ($exist) {
        // stop following if following
        $this->favoriter()->detach($favId);
        return true;
    } else {
        // do nothing if not following
        return false;
    }
}
    

public function is_favorite($favId) {
    return $this->favoriter()->where('fav_id', $favId)->exists();
}
}
