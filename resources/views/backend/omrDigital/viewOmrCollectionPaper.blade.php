@extends('backend.common.baseFile')
@section('content')
@section('title', 'View Mock Paper')
@section('papgeTitle', 'View Mock Paper')

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="edu_main_wrapper">
                <div class="edu_admin_informationdiv">
                    <div class="question_paper_views">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12 padderBottom20">
                                <div class="responsive_center">
                                    <h4 class="edu_sub_title">
                                        <span>Paper Name: </span>{{$paper->paper_name}}                  			</h4>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12 padderBottom20">
                                 <div class="responsive_center text-right">
                                    <h4 class="edu_sub_title">
                                        <span>Time:</span> {{$paper->exam_time}} Min
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="edu_questionView_section">
                            <ol>
                                @foreach ($questions as $item)
                                @if($item->question_type == 'Normal')
                                @php
                                $question =json_Decode($item->questionBankInfo[0]->question);
                                $lang=$item->language_type;
                                @endphp
                                <li>
                                    <div class="edu_single_questionView_wrap">
                                        <div class="edu_singleView_question questionsDiv">
                                            <div class="quest">
                                                @if($item->language_type !='Both')
                                                Q 1. {!! $question->$lang !!}
                                                @else

                                                @endif
                                            </div>
                                            <div class="edu_questionView_options">
                                                <ul class="question_options">
                                                    <li>A.&nbsp; एफ-फैक्टर </li><li>B.&nbsp; कॉल-फैक्टर </li><li>C.&nbsp; टीआई -प्लाजिमड</li><li>D.&nbsp; आर -फैक्टर </li><li>E.&nbsp; इनमे से कोई नहीं /एक से अधिक </li>                    									</ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @else

                                @endif
                                @endforeach



                                            <li>
                                                <div class="edu_single_questionView_wrap">
                                                    <div class="edu_singleView_question questionsDiv">
                                                        <div class="quest">Q 1.
                                                            नील -हरी शैवाल पर परजीवी वायरस कहा जाता है -                									</div>
                                                        <div class="edu_questionView_options">
                                                            <ul class="question_options">
                                                                <li>A.&nbsp; लेम्डाफेज </li><li>B.&nbsp; सयानोफेज </li><li>C.&nbsp; बैक्टोरियो फेज </li><li>D.&nbsp; कोली फेज </li><li>E.&nbsp; इनमे से कोई नहीं /एक से अधिक </li>                    									</ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="edu_single_questionView_wrap">
                                                    <div class="edu_singleView_question questionsDiv">
                                                        <div class="quest">Q 1.
                                                            द्वि-स्ताम्भीय आर.एन.ए. पाया जाता है -                									</div>
                                                        <div class="edu_questionView_options">
                                                            <ul class="question_options">
                                                                <li>A.&nbsp; स्माल पॉक्स विषाणु में </li><li>B.&nbsp; वुंड ट्यूमर विषाणु में </li><li>C.&nbsp; टोबैको मोजैक विषाणु में </li><li>D.&nbsp; इन्फ्लूएंजा विषाणु में </li><li>E.&nbsp; इनमे से कोई नहीं /एक से अधिक </li>                    									</ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="edu_single_questionView_wrap">
                                                    <div class="edu_singleView_question questionsDiv">
                                                        <div class="quest">Q 1.
                                                            चेचक का कारण है ?                									</div>
                                                        <div class="edu_questionView_options">
                                                            <ul class="question_options">
                                                                <li>A.&nbsp; वैरिओला </li><li>B.&nbsp; कॉक्सिएला</li><li>C.&nbsp; एइडीस</li><li>D.&nbsp; फ्रेन्न्निसेल्ला</li><li>E.&nbsp; इनमे से कोई नहीं /एक से अधिक </li>                    									</ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                                                    </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
