@extends('backend.common.baseFile')
@section('content')

@section('title', 'Add Institute')
@section('papgeTitle', 'Add Institute')

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card" >
                <div class="card-body">
                    <form data="{{ route('admin.newInstitute') }}" id="insertFormData"  method="POST" enctype="multipart/form-data">
                        {{-- <form action="{{ route('admin.newInstitute') }}" method="POST" enctype="multipart/form-data"> --}}
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Institute Name : <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="instituteName" id="instituteName" placeholder="Enter Institute Name" required value="" />
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>Damian URL: (<span class="text-danger"><strong>Eg -> abc.{{$domain}}</strong></span>) </label>
                                <div class="input-group">
                                    {{-- <input type="text" class="form-control"> --}}
                                    
                                    <input type="text" class="form-control" id="domainName" placeholder="Enter Domain Name" name="domainName" value="aditya" />

                                    <div class="input-group-append">
                                        <span class="input-group-text"><b>.{{$domain}}</b></span>
                                        
                                        <button class="btn btn-primary" type="button" onClick="serachDomain('{{route('admin.searchDomain')}}')" >Search</button>
                                    </div>
                                    
                                    
                                </div>
                                <div class="text-danger domainError"></div>
                                <div class="text-primary domainSuccess"></div>
                                
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class=" col-md-4">
                                <div class="form-group">
                                    <label for="name">Enter Name : <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="instituteOwnerName" name="instituteOwnerName" placeholder="Enter Owner Name" required value="" />
                                </div>
                                
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="month">Enter Email Id : <span style="color: red;">*</span></label>
                                <input type="email" class="form-control" id="instituteOwnerEmail" name="instituteOwnerEmail" placeholder="Enter Email Id"  required value="" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount">Enter Mobile No : <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="instituteOwnerMobileNo" name="instituteOwnerMobileNo" placeholder="Enter Mobile Number"  required="" onkeypress="return onlyNumberKey(event)" maxlength="10" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Institute Address : <span style="color: red;">*</span></label>
                                    <textarea class="form-control" name="instituteAddress" id="instituteAddress" rows="3" required placeholder="Entrr Institute address" ></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="for-group">
                                    <button type="submit" class="btn btn-success" >Submit</button>
                                </div>
                            </div>
                        </div>
                   </form>

                    
                </div>
            </div>

         
        </div>
    </div>
</section>


<script>
    function serachDomain(url){
       $(".domainError").html('');
       $(".domainSuccess").html('');
        var domain = $("#domainName").val();
        var token = $('meta[name="csrf-token"]').attr('content');
        if(domain !=''){
            $.ajax ({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                data: {
                    'domain':domain,
                    '_token': token
                    },
                success: function(data)
                {
                    if(data.statusCode == '200'){
                        $(".domainSuccess").html('✔ '+data.message);
                    } else {
                        $(".domainError").html('❌ ' +data.message);
                    }
                },
                error: function(error)
                {
                    swal("Failed", "Fail to update status try again", "error");
                }
            });
        } else {
            $(".domainError").html('Enter Doamin Name !');
        }
    }
</script>

@endsection