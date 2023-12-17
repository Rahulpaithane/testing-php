@extends('backend.common.baseFile')
@section('content')

@section('title', 'Upload Bulk Question')
@section('papgeTitle', 'Upload Bulk Question')

<section>

    <div class="row">
        <div class="col-md-12 questionFormat">
            <div class="edu_btn_wrapper sectionHolder padderBottom30 text-right">
                <a href="{{route($shared['routePath'].'.questionManage')}}" class="btn btn-primary" > <i class="fas fa-plus"></i> New Question</a>
                <a href="{{route($shared['routePath'].'.questionBankList')}}" class="btn btn-primary" > <i class="fas fa-list"></i> Question List</a>
             </div>

             <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link"  href="{{ route($shared['routePath'].'.bulkUploadQuestion') }}">Excel</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active"  href="{{ route($shared['routePath'].'.bulkWordOfficeUploadQuestion') }}">Word Office</a>
                </li>
               
            </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                   
                    <div class="tab-pane container-fluid active" id="wordOffice">
                        <div class="card-body">
                            <form data="{{ route($shared['routePath'].'.bulkUploadQuestion') }}" id="questionInsertFormData"  method="POST" enctype="multipart/form-data">
                                {{-- <form action="{{ route($shared['routePath'].'.bulkWordOfficeUploadQuestion') }}"   method="POST" enctype="multipart/form-data"> --}}
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="from-group">
                                                <label>Select Subject <span class="text-danger">*</span></label>
                                                <select name="subject" id="subject" class="form-control" onChange="fetchChapter()" url="{{route($shared['routePath'].'.fetchChapter')}}" required >
                                                    <option selected="selected" hidden="true"  value="" >Selct Subject</option>
                                                    @foreach ($subject as $item)
                                                        <option value="{{$item->id}}">{{$item->name}} ( {{$item->class->name}} )</option>
                                                    @endforeach 
                                                </select>
                                            </div>
                                            <div class="error subjectError text-danger" ></div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="from-group">
                                                <label>Select Chapter <span class="text-danger">*</span></label>
                                                <select name="chapter" id="chapter" class="form-control" required >
                                                    <option selected="selected" hidden="true" value="" >Select</option>
                
                                                </select>
                                            </div>
                                            <div class="error chapterError text-danger" ></div>
                                        </div>
                
                                        <div class="col-md-3">
                                            <div class="from-group">
                                                <label>Question Type <span class="text-danger">*</span></label>
                                                <select name="questionType" id="questionType" class="form-control" required >
                                                    <option selected="selected"  value="Normal" >Normal</option>
                                                    {{-- <option  value="Group" >Group</option> --}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="from-group">
                                                <label>Language Type <span class="text-danger">*</span></label>
                                                <select name="languageType" id="languageType" class="form-control" required >
                                                    <option selected="selected"  value="English" >English</option>
                                                    <option  value="Hindi" >Hindi</option>
                                                    <option  value="Both" >Both</option>
                                                </select>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <div class="row"> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Select DOC File</label>
                                                <input type="file" class="form-control" name="doc_file" id="doc_file" required />
                                            </div>
                                        </div>
        
                                        <div class="col-md-3 mt-4">
                                            <button type="submit" class="btn btn-primary">Upload Bulk Question</button>
                                        </div>
                                        <div class="col-md-3 mt-4">
                                            <a href="{{ url('assets/samplequestionFormat.docx')}}" download="samplequestionFormat.docx" class="btn btn-info">Download Demo File</a>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            Question: 01<br/>
                                            Level: Low <br/>
                                            Tag: SSC-[2017, 2018, 2019], Banking-[2017, 2018, 2019] <br/>
                                            Answer: b <br/>
                                            Hindi: <br/>
                                            Q: भगवान महावीर को मोक्ष निम्न में से किस स्थान पर प्राप्त हुआ था? <br/>
                                                (a) सोनागिरि <br/>
                                                (b) श्रवणबेलगोला <br/>
                                                (c) पावापुरी <br/>
                                                (d) माउंट आबू <br/>
                                                (e)  <br/>

                                            Solution: जैन धर्म के वास्तविक संस्थापक 24 वें तथा अंतिम तीर्थंकर महावीर स्वामी थे। इनका जन्म 540/599 ई.पु. वैशाली के कुण्डग्राम में हुआ था, जिसे आधुनिक बसाढ़ कहते है। बारह वर्ष की कठिन तपस्या के बाद महावीर को अंग देश के जृम्भिक ग्राम के निकट ऋजुपालिका नदी के तट पर साल वृक्ष के नीचे ज्ञान (कैवल्य) प्राप्त हुआ। ज्ञान प्राप्ति के बाद महावीर स्वामी ने अपना पहला उपदेश राजगृह में बितूलाचल पहाड़ी पर बराकर नदी के तट पर दिया।
                                            महावीर की मृत्यु (मोक्ष की प्राप्ति) राजगृह के निकट पावापुरी में 72 वर्ष की अवस्था में हुई। <br/>
                                            English: <br/>
                                            Q: At which of the following places did Lord Mahavira attain salvation? <br/>
                                            (a) sonagiri <br/>
                                            (b) Shravanabelagola <br/>
                                            (c) Pawapuri<br/>
                                            (d) mount abu<br/>
                                            (e) <br/>

                                            Solution: The real founder of Jainism was Mahavir Swami, the 24th and last Tirthankara. He was born in 540/599 BC. It happened in Kundagram of Vaishali, which is called modern Basadh. After twelve years of hard penance, Mahavira attained enlightenment (Kaivalya) under a sal tree on the banks of the river Rijupalika near the village of Jrimbhik in Anga. After attaining enlightenment, Mahavir Swami gave his first sermon at Rajagriha on the Bitulachal hill on the banks of the Barakar river.
                                            Mahavira died (attainment of salvation) at the age of 72 at Pawapuri near Rajagriha.<br/>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <u><h6>Both Languege Format</h6></u>
                                                    <p>
                                                    Question:<br/>
                                                    Level:  <br/>
                                                    Tag:  <br/>
                                                    Answer:  <br/>
                                                    Hindi: <br/>
                                                    Q:  <br/>
                                                        (a)  <br/>
                                                        (b)  <br/>
                                                        (c)  <br/>
                                                        (d)  <br/>
                                                        (e)  <br/>

                                                    Solution: <br/>
                                                    English: <br/>
                                                    Q:  <br/>
                                                    (a)  <br/>
                                                    (b)  <br/>
                                                    (c) <br/>
                                                    (d) <br/>
                                                    (e) <br/>

                                                    Solution: <br/>
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <u><h6>English Languege Format</h6></u>
                                                    <p>
                                                    Question:<br/>
                                                    Level:  <br/>
                                                    Tag:  <br/>
                                                    Answer:  <br/>
                                                    English: <br/>
                                                    Q:  <br/>
                                                    (a)  <br/>
                                                    (b)  <br/>
                                                    (c) <br/>
                                                    (d) <br/>
                                                    (e) <br/>

                                                    Solution: <br/>
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <u><h6>Hindi Languege Format</h6></u>
                                                    <p>
                                                    Question:<br/>
                                                    Level:  <br/>
                                                    Tag:  <br/>
                                                    Answer:  <br/>
                                                    Hindi: <br/>
                                                    Q:  <br/>
                                                        (a)  <br/>
                                                        (b)  <br/>
                                                        (c)  <br/>
                                                        (d)  <br/>
                                                        (e)  <br/>

                                                    Solution: <br/>
                                         
                                                    </p>
                                                </div>
                                                <div class="col-md-12">
                                                    {{-- <button type="button" id="copyButton">Copy Content</button> --}}

                                                    <script>
                                                        $(document).ready(function() {
                                                            $("#copyButton").click(function() {
                                                                var content = $(".copyy").text(); // Get the HTML content of the specified div
                                                                copyToClipboard(content);
                                                                alert("Content copied to clipboard!");
                                                            });
                                                        });

                                                        function copyToClipboard(text) {
                                                            var dummy = document.createElement("textarea");
                                                            document.body.appendChild(dummy);
                                                            dummy.value = text;
                                                            dummy.select();
                                                            document.execCommand("copy");
                                                            document.body.removeChild(dummy);
                                                        }
                                                    </script>

                                                </div>
                                            </div>
                                        </div>


                                    </div>
                            </form>
                        </div>
                    </div>
                    
                </div>
                  
          
            
        </div>
    </div>
    

    
</section>

  <!-- The Modal -->
  <div class="modal fade" id="badInsertionModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Error Log Question</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12"><Strong style="color: red;">Something error some question! Plz Update file then Upload </Strong></div>
                <div class="col-md-12 logQuestion">

                </div>
            </div>
        </div>
        
        
      </div>
    </div>
  </div>

@endsection