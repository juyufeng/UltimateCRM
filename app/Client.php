<?php

namespace app;

use App\Invoice;
use App\Project;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['full_name', 'email', 'number', 'address', 'stripe_customer_id'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function addInvoice(Invoice $invoice)
    {
        return $this->invoices()->save($invoice);
    }

    public function addProject(Project $project)
    {
        return $this->projects()->save($project);
    }
    
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($client) {
            $client->invoices()->delete();
            $client->projects()->delete();
            User::find($client->user_id)->delete();
        });
    }
}
