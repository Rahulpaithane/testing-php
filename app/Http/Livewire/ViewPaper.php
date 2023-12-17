<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\QuestionBank;
use App\Models\QuestionBankInfo;
use App\Models\ExamPaper;

class ViewPaper extends Component
{
    public $paper, $questions, $paperLanguage, $language ;

    public function mount(){
        $this->paper = ExamPaper::find(request('id')); // Assuming you have the paper ID
        // dd($paper->question_id);
        $this->paperLanguage=$this->paper->language_type;
        $this->questions = QuestionBank::whereIn('id',  json_decode($this->paper->question_id) )->with('questionBankInfo')->get();
        // dd($this->questions[0]->language_type);
        $this->language=$this->paper->language_type =='Both' ? 'English' : $this->paper->language_type;
    }

    public function render()
    {
        // dd($this->language);
        return view('livewire.view-paper',[])
        ->extends('backend.common.baseFile')
        ->section('content');
        // return view('livewire.view-paper');
    }
}
