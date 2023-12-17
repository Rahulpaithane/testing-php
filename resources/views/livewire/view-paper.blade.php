<section>
    <div class="row">
        <div class="col-md-12">
            <div class="edu_main_wrapper">				
                <div class="edu_admin_informationdiv">
                    <div class="question_paper_views">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-12 col-12 padderBottom20">
                                <div class="responsive_center">
                                    <h5 class="edu_sub_title">
                                        <span>Paper Name : {{$paper->paper_name}}</span>,  &#160;&#160;  <hr/>              			
                                        <span>Total Question: {{$paper->total_question}}</span>,  &#160;&#160;                			
                                        <span>Total Marks: {{$paper->total_marks}}</span>,  &#160;&#160;                			
                                        <span>Number/Question: {{$paper->per_question_no}}</span>,  &#160;&#160; 
                                        <span>Negative Marking: {{$paper->negative_marking_type}}</span>,  &#160;&#160;                			
                                        <span>number/Negative: {{$paper->per_negative_no}}</span>,  &#160;&#160; 
                                        <hr/>  
                                        <span>Class : {{$paper->batch->class->name}}</span>,&#160;&#160;&#160;  
                                        <span>Batch : {{$paper->batch->name}}</span>,&#160;&#160;&#160; 
                                        <span>Exam Cat : {{$paper->examCategory->category_name}}</span>,&#160;&#160;&#160;   
                                        <span>Exam Sub-Cat : {{$paper->examSubCategory == null ? '' : $paper->examSubCategory->sub_category_name}}</span>&#160;&#160;&#160;       			
                                    </h5>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-12 padderBottom20">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="text-left">
                                            <h5 class="edu_sub_title">
                                            <span>Paper Language : {{$paper->language_type !='Both' ? $paper->language_type : "English, Hindi" }}</span><br/>
                                            <span>Live Paper : {{$paper->is_live =='1' ? 'Yes' : "No" }}</span><br/>
                                            <hr/>
                                            <span>Exam Date : {{$paper->exam_date}}</span><br/>
                                            <span>Exam Time : {{$paper->exam_time}}</span>
                                            
                                            
                                            
                                            </h5>               			 
                                        </div>
                                         
                                    </div>
                                    <div class="col-md-3">
                                        <div class="responsive_center text-right">
                                            {{-- <h4 class="edu_sub_title">
                                                <span>Time:</span> {{$paper->exam_time}} Min 
                                            </h4> --}}
                                       
                                            {{-- <div class="form-group edu_radio_holder_wrapper text-right"> --}}
                                                @if($paper->language_type =='Both')
                                                    <div class="edu_radio_holder">
                                                        <label for="radio">Eng</label>
                                                        <input type="radio" wire:model="language" class="ansRadioChck"  name="language" value="English" />
                                                    </div>
                                                    <div class="edu_radio_holder">
                                                        <label for="radio">Hin</label>
                                                        <input type="radio" wire:model="language" class="ansRadioChck" name="language" value="Hindi" />
                                                    </div>
                                                @endif
                                                
                                        
                                            {{-- </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="edu_questionView_section">
                            <ol>
                                @foreach ($questions as $key=>$item)
                                    @if($item->question_type == 'Normal')
                                        @php 
                                        $question =json_decode($item->questionBankInfo[0]->question);
                                        $option =json_decode($item->questionBankInfo[0]->option);
                                        $lang=$item->language_type; // Paper Language English, Hindi, Both
                                        // dd($option->$language[0]);
                                        @endphp
                                        <li>
                                            <div class="edu_single_questionView_wrap">
                                                <div class="edu_singleView_question questionsDiv">
                                                    <div class="quest">
                                                        @if($item->language_type !='Both')
                                                        Q {{$key+1}}. {!! $question->$lang !!} 
                                                        @else            
                                                        Q {{$key+1}}. {!! $question->$language !!} 
                                                        @endif
                                                    </div>
                                                    <div class="edu_questionView_options">
                                                        <ul class="question_options">
                                                            @if($item->language_type !='Both')
                                                            <li>A.&nbsp; {!! $option->$lang[0] !!} </li><li>B.&nbsp; {!! $option->$lang[1] !!} </li><li>C.&nbsp; {!! $option->$lang[2] !!}</li><li>D.&nbsp; {!! $option->$lang[3] !!} </li>
                                                            <li>E.&nbsp; {!! $option->$lang[4] !!} </li>  
                                                            @else  
                                                            <li>A.&nbsp; {!! $option->$language[0] !!} </li><li>B.&nbsp; {!! $option->$language[1] !!} </li><li>C.&nbsp; {!! $option->$language[2] !!}</li><li>D.&nbsp; {!! $option->$language[3] !!} </li>
                                                            <li>E.&nbsp; {!! $option->$language[4] !!} </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @else
                                        @php 
                                            $paragraph =json_decode($item->pragraph);
                                            $option =json_decode($item->questionBankInfo[0]->option);
                                            $lang=$item->language_type; // Paper Language English, Hindi, Both
                                            // dd($paragraph);
                                        @endphp

                                    <li>
                                        <div class="edu_single_questionView_wrap">
                                            <div class="edu_singleView_question questionsDiv">
                                                <div class="quest">
                                                    @if($item->language_type !='Both')
                                                     {!! $paragraph->$lang !!} 
                                                    @else            
                                                     {!! $paragraph->$language !!} 
                                                    @endif
                                                </div>
                                                <ol>
                                                   @foreach ($item->questionBankInfo as $info)
                                                   @php
                                                        $question =json_decode($info->question);
                                                        $option =json_decode($info->option);
                                                        
                                                   @endphp
                                                    <li>
                                                        <div class="quest">
                                                            @if($item->language_type !='Both')
                                                            Q {{$key+1}}. {!! $question->$lang !!} 
                                                            @else            
                                                            Q {{$key+1}}. {!! $question->$language !!} 
                                                            @endif
                                                        </div>
                                                        <div class="edu_questionView_options">
                                                            <ul class="question_options">
                                                                @if($item->language_type !='Both')
                                                                <li>A.&nbsp; {!! $option->$lang[0] !!} </li><li>B.&nbsp; {!! $option->$lang[1] !!} </li><li>C.&nbsp; {!! $option->$lang[2] !!}</li><li>D.&nbsp; {!! $option->$lang[3] !!} </li>
                                                                <li>E.&nbsp; {!! $option->$lang[4] !!} </li>  
                                                                @else  
                                                                <li>A.&nbsp; {!! $option->$language[0] !!} </li><li>B.&nbsp; {!! $option->$language[1] !!} </li><li>C.&nbsp; {!! $option->$language[2] !!}</li><li>D.&nbsp; {!! $option->$language[3] !!} </li>
                                                                <li>E.&nbsp; {!! $option->$language[4] !!} </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </li>
                                                    @php $key++; @endphp
                                                   @endforeach
                                                </ol>
                                            </div>
                                        </div>
                                    </li>

                                    @endif
                                @endforeach                                     
                        
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>