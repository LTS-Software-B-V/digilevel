<?php
namespace App\Http\Livewire\Settings\Workorders;

use Livewire\Component;




//Models
 
use App\Models\maintenanceCompany;
use App\Models\Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;

//Datatable
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithPerPagePagination;

use Illuminate\Support\Facades\Http;

class Errors extends Component
{


    use WithPerPagePagination;
    use WithSorting;
    use WithBulkActions;
    use WithCachedRows;

    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $keyword;
    public $cntFilters;

    public $code;

    #[Validate('required', message: 'Vul minimaal een foutmelding')]
    public $error;

    
    public $edit_id;
public $data;

 

    public $filters = [
        'keyword'   => '', 
 
        
    ];

 

  
    public function render()
    {
        return view('livewire.settings.workorders.errors',[
            'items' => $this->rows,
            ]);
    }


    protected $rules = [
        'code' => 'required',
        'error' => 'required',
    ];

    
    public function getRowsQueryProperty()
    {
        $query = Error::query()->when($this->filters['keyword'], function ($query) {
            $query->where('code', 'like', '%' . $this->filters['keyword'] . '%')
                ->Orwhere('error', 'like', '%' . $this->filters['keyword'] . '%')
 ;
        });
        Session()->put('workorder_error_filter', json_encode($this->filters));

        return $query->orderBy($this->sortField, $this->sortDirection);
    }


    public function countFilters(){
   
        $this->cntFilters = ( $this->filters['keyword'] ? 1 : 0);
    }


    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });
    }

    public function mount(Request $request)
    {
    $this->countFilters();
    }


public function sortBy($field)
{
    if ($this->sortField === $field) {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortDirection = 'asc';
    }

    $this->sortField = $field;
}

public function clear(){
$this->name =NULL;
$this->error =NULL;

  
 
}

 
public function save(){
    $validated = $this->validate();
 
    if(!$this->edit_id){
        Error::create($this->all());
    }else{
        $this->data->update($this->all());
    }
    
 
    $this->reset();
    $this->dispatch('close-crud-modal');
    pnotify()->addWarning('Gegevens opgeslagen');

}
 

    public function updatedFilters()
    {
        Session()->put('workorder_error_filter', json_encode($this->filters));
        $this->countFilters();
    
    }

    public function resetFilters()
    {
        $this->reset('filters');
        session()->pull('workorder_error_filter', '');
        $this->gotoPage(1);
        return redirect(request()->header('Referer'));

    }

    public function edit($id)
    {
    
        $this->edit_id = $id;
        $this->data = $item = Error::where('id', $id)->first();
         $this->code =  strtoupper($this->data->code);
        $this->error =  $this->data->error;
 



    }


    public function delete($id){
        $item= Error::find($id);
        $item->delete();  
        $this->dispatch('close-crud-modal');
        pnotify()->addWarning('Gegevens verwijderd');
    }

    public function updateCode() {
     
        $this->code  = str_replace(" ","",strtoupper($this->code));
    }

    



    }

 