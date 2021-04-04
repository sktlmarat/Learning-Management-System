<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = ['date','duration','end_date','session_days','frequency','description','course_id'];
    protected $with = ['course'];
    /**
     * @var mixed
     */
    private $end_date;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course(){
        return $this->belongsTo(Course::class);
    }
}
