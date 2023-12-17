<div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 col-12">
    {{-- <a href="{{ route($shared['routePath'].'.manageStudent')}}"> --}}
    <div class="edu_color_boxes box_left">
        <div class="edu_dash_box_data">
            <p>Total Student</p>
            <h3>{{$userData['students']->count()}}</h3>
        </div>
        <div class="edu_dash_box_icon">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 30 30" enable-background="new 0 0 30 30" xml:space="preserve">
                <g>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M14.345,25.468c-0.109-0.076-0.215-0.159-0.329-0.229
                        c-1.589-0.974-3.273-1.72-5.122-2.026c-0.802-0.134-1.621-0.177-2.434-0.233c-0.463-0.033-0.701-0.244-0.701-0.71
                        c0-0.163,0-0.326,0-0.495c0.199-0.035,0.394-0.056,0.582-0.102c1.106-0.271,1.868-1.242,1.872-2.378
                        c0.002-0.804,0.001-1.607,0-2.411c-0.002-1.358-0.999-2.414-2.353-2.496c-0.026-0.002-0.052-0.006-0.096-0.012
                        c0-0.232-0.018-0.464,0.004-0.691c0.028-0.295,0.287-0.52,0.589-0.521c2.788-0.01,5.444,0.521,7.897,1.907
                        c0.133,0.074,0.132,0.164,0.132,0.28c0,3.372,0,6.745,0,10.117C14.373,25.468,14.359,25.468,14.345,25.468z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M15.613,25.468c0-3.365,0.002-6.731-0.004-10.097
                        c0-0.176,0.06-0.26,0.208-0.342c1.664-0.914,3.433-1.498,5.32-1.705c0.805-0.088,1.615-0.121,2.424-0.159
                        c0.434-0.021,0.678,0.243,0.68,0.681c0,0.171,0,0.34,0,0.515c-0.213,0.036-0.422,0.06-0.623,0.109
                        c-1.051,0.264-1.822,1.247-1.828,2.331c-0.006,0.824-0.004,1.648-0.002,2.473c0.004,1.329,1.008,2.393,2.334,2.474
                        c0.031,0.002,0.064,0.006,0.115,0.009c0,0.237,0.018,0.469-0.006,0.696c-0.029,0.311-0.297,0.52-0.631,0.52
                        c-0.941,0.003-1.873,0.097-2.797,0.288c-1.846,0.385-3.529,1.155-5.114,2.162c-0.015,0.01-0.023,0.03-0.035,0.046
                        C15.64,25.468,15.626,25.468,15.613,25.468z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M15.368,4.532c0.224,0.052,0.45,0.093,0.669,0.159
                        c1.646,0.491,2.75,2.089,2.639,3.808c-0.111,1.682-1.385,3.093-3.058,3.385c-0.054,0.009-0.107,0.012-0.162,0.055
                        c0.357,0,0.718-0.024,1.072,0.007c0.363,0.032,0.729,0.096,1.082,0.187c0.352,0.091,0.689,0.234,1.033,0.355
                        c-0.002,0.018-0.006,0.038-0.008,0.056c-0.107,0.033-0.215,0.065-0.322,0.098c-1.096,0.33-2.146,0.767-3.144,1.332
                        c-0.104,0.06-0.186,0.089-0.312,0.017c-1.073-0.609-2.205-1.083-3.396-1.409c-0.032-0.009-0.062-0.025-0.118-0.049
                        c1.022-0.541,2.101-0.654,3.214-0.606c-0.769-0.12-1.454-0.414-2.017-0.944c-1.077-1.016-1.472-2.267-1.082-3.693
                        c0.39-1.427,1.349-2.325,2.798-2.676c0.125-0.03,0.25-0.054,0.375-0.081C14.877,4.532,15.123,4.532,15.368,4.532z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M4.532,16.636c0.099-0.315,0.235-0.606,0.524-0.799
                        c0.401-0.267,0.825-0.307,1.254-0.089c0.436,0.223,0.667,0.595,0.672,1.084c0.009,0.817,0.004,1.633,0.002,2.449
                        c-0.002,0.628-0.439,1.134-1.059,1.23c-0.582,0.092-1.168-0.291-1.348-0.881c-0.014-0.045-0.031-0.089-0.046-0.133
                        C4.532,18.544,4.532,17.59,4.532,16.636z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M25.469,19.498c-0.1,0.315-0.236,0.607-0.525,0.798
                        c-0.4,0.267-0.824,0.308-1.254,0.089c-0.436-0.221-0.666-0.593-0.672-1.083c-0.01-0.81-0.004-1.619-0.002-2.429
                        c0-0.652,0.447-1.172,1.078-1.254c0.59-0.078,1.154,0.305,1.332,0.903c0.012,0.039,0.027,0.075,0.043,0.113
                        C25.469,17.59,25.469,18.544,25.469,19.498z"/>
                </g>
            </svg>
        </div>
        <div class="edu_dash_info">
            <ul>
                <li><p>Active <span>{{$userData['students']->where('status', 1)->count()}}</span></p></li>
                <li><p>Inactive <span>{{$userData['students']->where('status', 0)->count()}}</span></p></li>
            </ul>
        </div>
    </div>
    {{-- </a> --}}
</div>

<div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 col-12">
    {{-- <a href="{{ route($shared['routePath'].'.manageTeachers')}}"> --}}
    <div class="edu_color_boxes box_left">
        <div class="edu_dash_box_data">
            <p>Total Teachers</p>
            <h3>{{$userData['teachers']->count()}}</h3>
        </div>
        <div class="edu_dash_box_icon">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 30 30" enable-background="new 0 0 30 30" xml:space="preserve">
                <g>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M14.345,25.468c-0.109-0.076-0.215-0.159-0.329-0.229
                        c-1.589-0.974-3.273-1.72-5.122-2.026c-0.802-0.134-1.621-0.177-2.434-0.233c-0.463-0.033-0.701-0.244-0.701-0.71
                        c0-0.163,0-0.326,0-0.495c0.199-0.035,0.394-0.056,0.582-0.102c1.106-0.271,1.868-1.242,1.872-2.378
                        c0.002-0.804,0.001-1.607,0-2.411c-0.002-1.358-0.999-2.414-2.353-2.496c-0.026-0.002-0.052-0.006-0.096-0.012
                        c0-0.232-0.018-0.464,0.004-0.691c0.028-0.295,0.287-0.52,0.589-0.521c2.788-0.01,5.444,0.521,7.897,1.907
                        c0.133,0.074,0.132,0.164,0.132,0.28c0,3.372,0,6.745,0,10.117C14.373,25.468,14.359,25.468,14.345,25.468z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M15.613,25.468c0-3.365,0.002-6.731-0.004-10.097
                        c0-0.176,0.06-0.26,0.208-0.342c1.664-0.914,3.433-1.498,5.32-1.705c0.805-0.088,1.615-0.121,2.424-0.159
                        c0.434-0.021,0.678,0.243,0.68,0.681c0,0.171,0,0.34,0,0.515c-0.213,0.036-0.422,0.06-0.623,0.109
                        c-1.051,0.264-1.822,1.247-1.828,2.331c-0.006,0.824-0.004,1.648-0.002,2.473c0.004,1.329,1.008,2.393,2.334,2.474
                        c0.031,0.002,0.064,0.006,0.115,0.009c0,0.237,0.018,0.469-0.006,0.696c-0.029,0.311-0.297,0.52-0.631,0.52
                        c-0.941,0.003-1.873,0.097-2.797,0.288c-1.846,0.385-3.529,1.155-5.114,2.162c-0.015,0.01-0.023,0.03-0.035,0.046
                        C15.64,25.468,15.626,25.468,15.613,25.468z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M15.368,4.532c0.224,0.052,0.45,0.093,0.669,0.159
                        c1.646,0.491,2.75,2.089,2.639,3.808c-0.111,1.682-1.385,3.093-3.058,3.385c-0.054,0.009-0.107,0.012-0.162,0.055
                        c0.357,0,0.718-0.024,1.072,0.007c0.363,0.032,0.729,0.096,1.082,0.187c0.352,0.091,0.689,0.234,1.033,0.355
                        c-0.002,0.018-0.006,0.038-0.008,0.056c-0.107,0.033-0.215,0.065-0.322,0.098c-1.096,0.33-2.146,0.767-3.144,1.332
                        c-0.104,0.06-0.186,0.089-0.312,0.017c-1.073-0.609-2.205-1.083-3.396-1.409c-0.032-0.009-0.062-0.025-0.118-0.049
                        c1.022-0.541,2.101-0.654,3.214-0.606c-0.769-0.12-1.454-0.414-2.017-0.944c-1.077-1.016-1.472-2.267-1.082-3.693
                        c0.39-1.427,1.349-2.325,2.798-2.676c0.125-0.03,0.25-0.054,0.375-0.081C14.877,4.532,15.123,4.532,15.368,4.532z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M4.532,16.636c0.099-0.315,0.235-0.606,0.524-0.799
                        c0.401-0.267,0.825-0.307,1.254-0.089c0.436,0.223,0.667,0.595,0.672,1.084c0.009,0.817,0.004,1.633,0.002,2.449
                        c-0.002,0.628-0.439,1.134-1.059,1.23c-0.582,0.092-1.168-0.291-1.348-0.881c-0.014-0.045-0.031-0.089-0.046-0.133
                        C4.532,18.544,4.532,17.59,4.532,16.636z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M25.469,19.498c-0.1,0.315-0.236,0.607-0.525,0.798
                        c-0.4,0.267-0.824,0.308-1.254,0.089c-0.436-0.221-0.666-0.593-0.672-1.083c-0.01-0.81-0.004-1.619-0.002-2.429
                        c0-0.652,0.447-1.172,1.078-1.254c0.59-0.078,1.154,0.305,1.332,0.903c0.012,0.039,0.027,0.075,0.043,0.113
                        C25.469,17.59,25.469,18.544,25.469,19.498z"/>
                </g>
            </svg>
        </div>
        <div class="edu_dash_info">
            <ul>
                <li><p>Active <span>{{$userData['teachers']->where('status', 1)->count()}}</span></p></li>
                <li><p>Inactive <span>{{$userData['teachers']->where('status', 0)->count()}}</span></p></li>
            </ul>
        </div>
    </div>
    {{-- </a> --}}
</div>

<div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 col-12">
    {{-- <a href="{{ route($shared['routePath'].'.manageClasses')}}"> --}}
    <div class="edu_color_boxes box_left">
        <div class="edu_dash_box_data">
            <p>Total Classes</p>
            <h3>{{$userData['classes']->count()}}</h3>
        </div>
        <div class="edu_dash_box_icon">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 30 30" enable-background="new 0 0 30 30" xml:space="preserve">
                <g>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M14.345,25.468c-0.109-0.076-0.215-0.159-0.329-0.229
                        c-1.589-0.974-3.273-1.72-5.122-2.026c-0.802-0.134-1.621-0.177-2.434-0.233c-0.463-0.033-0.701-0.244-0.701-0.71
                        c0-0.163,0-0.326,0-0.495c0.199-0.035,0.394-0.056,0.582-0.102c1.106-0.271,1.868-1.242,1.872-2.378
                        c0.002-0.804,0.001-1.607,0-2.411c-0.002-1.358-0.999-2.414-2.353-2.496c-0.026-0.002-0.052-0.006-0.096-0.012
                        c0-0.232-0.018-0.464,0.004-0.691c0.028-0.295,0.287-0.52,0.589-0.521c2.788-0.01,5.444,0.521,7.897,1.907
                        c0.133,0.074,0.132,0.164,0.132,0.28c0,3.372,0,6.745,0,10.117C14.373,25.468,14.359,25.468,14.345,25.468z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M15.613,25.468c0-3.365,0.002-6.731-0.004-10.097
                        c0-0.176,0.06-0.26,0.208-0.342c1.664-0.914,3.433-1.498,5.32-1.705c0.805-0.088,1.615-0.121,2.424-0.159
                        c0.434-0.021,0.678,0.243,0.68,0.681c0,0.171,0,0.34,0,0.515c-0.213,0.036-0.422,0.06-0.623,0.109
                        c-1.051,0.264-1.822,1.247-1.828,2.331c-0.006,0.824-0.004,1.648-0.002,2.473c0.004,1.329,1.008,2.393,2.334,2.474
                        c0.031,0.002,0.064,0.006,0.115,0.009c0,0.237,0.018,0.469-0.006,0.696c-0.029,0.311-0.297,0.52-0.631,0.52
                        c-0.941,0.003-1.873,0.097-2.797,0.288c-1.846,0.385-3.529,1.155-5.114,2.162c-0.015,0.01-0.023,0.03-0.035,0.046
                        C15.64,25.468,15.626,25.468,15.613,25.468z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M15.368,4.532c0.224,0.052,0.45,0.093,0.669,0.159
                        c1.646,0.491,2.75,2.089,2.639,3.808c-0.111,1.682-1.385,3.093-3.058,3.385c-0.054,0.009-0.107,0.012-0.162,0.055
                        c0.357,0,0.718-0.024,1.072,0.007c0.363,0.032,0.729,0.096,1.082,0.187c0.352,0.091,0.689,0.234,1.033,0.355
                        c-0.002,0.018-0.006,0.038-0.008,0.056c-0.107,0.033-0.215,0.065-0.322,0.098c-1.096,0.33-2.146,0.767-3.144,1.332
                        c-0.104,0.06-0.186,0.089-0.312,0.017c-1.073-0.609-2.205-1.083-3.396-1.409c-0.032-0.009-0.062-0.025-0.118-0.049
                        c1.022-0.541,2.101-0.654,3.214-0.606c-0.769-0.12-1.454-0.414-2.017-0.944c-1.077-1.016-1.472-2.267-1.082-3.693
                        c0.39-1.427,1.349-2.325,2.798-2.676c0.125-0.03,0.25-0.054,0.375-0.081C14.877,4.532,15.123,4.532,15.368,4.532z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M4.532,16.636c0.099-0.315,0.235-0.606,0.524-0.799
                        c0.401-0.267,0.825-0.307,1.254-0.089c0.436,0.223,0.667,0.595,0.672,1.084c0.009,0.817,0.004,1.633,0.002,2.449
                        c-0.002,0.628-0.439,1.134-1.059,1.23c-0.582,0.092-1.168-0.291-1.348-0.881c-0.014-0.045-0.031-0.089-0.046-0.133
                        C4.532,18.544,4.532,17.59,4.532,16.636z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M25.469,19.498c-0.1,0.315-0.236,0.607-0.525,0.798
                        c-0.4,0.267-0.824,0.308-1.254,0.089c-0.436-0.221-0.666-0.593-0.672-1.083c-0.01-0.81-0.004-1.619-0.002-2.429
                        c0-0.652,0.447-1.172,1.078-1.254c0.59-0.078,1.154,0.305,1.332,0.903c0.012,0.039,0.027,0.075,0.043,0.113
                        C25.469,17.59,25.469,18.544,25.469,19.498z"/>
                </g>
            </svg>
        </div>
        <div class="edu_dash_info">
            <ul>
                <li><p>Active Govt <span>{{$userData['classes']->where('prepration', 'Govt')->where('status', 1)->count()}}</span></p></li>
                {{-- <li><p>Active Govt <span>{{$userData['classes']->where('prepration', 'Govt')->where('status', 0)->count()}}</span></p></li> --}}
                <li><p>Active School <span>{{$userData['classes']->where('prepration', 'School')->where('status', 1)->count()}}</span></p></li>
                {{-- <li><p>InActive School <span>{{$userData['classes']->where('prepration', 'School')->where('status', 1)->count()}}</span></p></li> --}}
            </ul>
        </div>
    </div>
    {{-- </a> --}}
</div> 
<div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 col-12">
    {{-- <a href="{{ route($shared['routePath'].'.batchManage')}}"> --}}
    <div class="edu_color_boxes box_center">
        <div class="edu_dash_box_data">
            <p>Total Batches</p>
            <h3>{{$userData['batches']->count()}}</h3>
        </div>
        <div class="edu_dash_box_icon">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 44.688 44.688"
                 xml:space="preserve">
                <g>
                    <g>
                        <path d="M25.013,39.119c-0.336,0.475-0.828,0.82-1.389,0.975l-2.79,0.762c-0.219,0.062-0.445,0.094-0.673,0.094
                            c-0.514,0-1.011-0.157-1.43-0.452c-0.615-0.428-1.001-1.103-1.062-1.834l-0.245-2.881c-0.058-0.591,0.101-1.183,0.437-1.659
                            l0.103-0.148H8.012c-0.803,0-1.454-0.662-1.454-1.463c0-0.804,0.651-1.466,1.454-1.466h12.046l2.692-3.845H8.012
                            c-0.803,0-1.454-0.662-1.454-1.465s0.651-1.465,1.454-1.465l16.811-0.043l6.304-9.039V8.497c0-1.1-0.851-1.988-1.948-1.988h-4.826
                            v3.819c0,1.803-1.474,3.229-3.274,3.229h-9.706c-1.804,0-3.227-1.427-3.227-3.229V6.509H3.268c-1.099,0-1.988,0.889-1.988,1.988
                            V42.65c0,1.1,0.89,2.037,1.988,2.037h25.909c1.1,0,1.949-0.938,1.949-2.037V30.438L25.013,39.119z M8.012,17.496h16.424
                            c0.801,0,1.453,0.661,1.453,1.464c0,0.803-0.652,1.465-1.453,1.465H8.012c-0.803,0-1.454-0.662-1.454-1.465
                            C6.558,18.157,7.209,17.496,8.012,17.496z"/>
                        <path d="M11.4,11.636h9.697c0.734,0,1.331-0.596,1.331-1.332V4.727c0-0.736-0.597-1.332-1.331-1.332h-1.461
                            C19.626,1.52,18.102,0,16.223,0c-1.88,0-3.402,1.519-3.413,3.395H11.4c-0.736,0-1.331,0.596-1.331,1.332v5.576
                            C10.069,11.039,10.664,11.636,11.4,11.636z M16.224,1.891c0.835,0,1.512,0.672,1.521,1.505H14.7
                            C14.71,2.563,15.388,1.891,16.224,1.891z"/>
                        <path d="M43.394,8.978c-0.045-0.248-0.186-0.465-0.392-0.609l-2.428-1.692c-0.164-0.115-0.353-0.17-0.539-0.17
                            c-0.296,0-0.591,0.14-0.772,0.403L22.064,31.573l3.973,2.771L43.238,9.682C43.38,9.477,43.437,9.224,43.394,8.978z"/>
                        <path d="M19.355,35.6l0.249,2.896c0.012,0.167,0.101,0.316,0.236,0.412c0.096,0.066,0.209,0.104,0.321,0.104
                            c0.049,0,0.099-0.007,0.147-0.021l2.805-0.768c0.127-0.035,0.237-0.113,0.313-0.22l1.053-1.51l-3.976-2.772l-1.053,1.51
                            C19.376,35.338,19.341,35.469,19.355,35.6z"/>
                    </g>
                </g>
            </svg>
        </div>
        <div class="edu_dash_info">
            <ul>
                <li><p>Active <span>{{$userData['batches']->where('status', 1)->count()}}</span></p></li>
                <li><p>Inactive <span>{{$userData['batches']->where('status', 0)->count()}}</span></p></li>
            </ul>
        </div>
    </div>
    {{-- </a> --}}
</div>


<div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 col-12">
    {{-- <a href="{{ route($shared['routePath'].'.manageBooks')}}"> --}}
    <div class="edu_color_boxes box_center">
        <div class="edu_dash_box_data">
            <p>Total Books/PDF</p>
            <h3>{{$userData['books']->count()}}</h3>
        </div>
        <div class="edu_dash_box_icon">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 30 30" enable-background="new 0 0 30 30" xml:space="preserve">
                <g>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M14.345,25.468c-0.109-0.076-0.215-0.159-0.329-0.229
                        c-1.589-0.974-3.273-1.72-5.122-2.026c-0.802-0.134-1.621-0.177-2.434-0.233c-0.463-0.033-0.701-0.244-0.701-0.71
                        c0-0.163,0-0.326,0-0.495c0.199-0.035,0.394-0.056,0.582-0.102c1.106-0.271,1.868-1.242,1.872-2.378
                        c0.002-0.804,0.001-1.607,0-2.411c-0.002-1.358-0.999-2.414-2.353-2.496c-0.026-0.002-0.052-0.006-0.096-0.012
                        c0-0.232-0.018-0.464,0.004-0.691c0.028-0.295,0.287-0.52,0.589-0.521c2.788-0.01,5.444,0.521,7.897,1.907
                        c0.133,0.074,0.132,0.164,0.132,0.28c0,3.372,0,6.745,0,10.117C14.373,25.468,14.359,25.468,14.345,25.468z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M15.613,25.468c0-3.365,0.002-6.731-0.004-10.097
                        c0-0.176,0.06-0.26,0.208-0.342c1.664-0.914,3.433-1.498,5.32-1.705c0.805-0.088,1.615-0.121,2.424-0.159
                        c0.434-0.021,0.678,0.243,0.68,0.681c0,0.171,0,0.34,0,0.515c-0.213,0.036-0.422,0.06-0.623,0.109
                        c-1.051,0.264-1.822,1.247-1.828,2.331c-0.006,0.824-0.004,1.648-0.002,2.473c0.004,1.329,1.008,2.393,2.334,2.474
                        c0.031,0.002,0.064,0.006,0.115,0.009c0,0.237,0.018,0.469-0.006,0.696c-0.029,0.311-0.297,0.52-0.631,0.52
                        c-0.941,0.003-1.873,0.097-2.797,0.288c-1.846,0.385-3.529,1.155-5.114,2.162c-0.015,0.01-0.023,0.03-0.035,0.046
                        C15.64,25.468,15.626,25.468,15.613,25.468z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M15.368,4.532c0.224,0.052,0.45,0.093,0.669,0.159
                        c1.646,0.491,2.75,2.089,2.639,3.808c-0.111,1.682-1.385,3.093-3.058,3.385c-0.054,0.009-0.107,0.012-0.162,0.055
                        c0.357,0,0.718-0.024,1.072,0.007c0.363,0.032,0.729,0.096,1.082,0.187c0.352,0.091,0.689,0.234,1.033,0.355
                        c-0.002,0.018-0.006,0.038-0.008,0.056c-0.107,0.033-0.215,0.065-0.322,0.098c-1.096,0.33-2.146,0.767-3.144,1.332
                        c-0.104,0.06-0.186,0.089-0.312,0.017c-1.073-0.609-2.205-1.083-3.396-1.409c-0.032-0.009-0.062-0.025-0.118-0.049
                        c1.022-0.541,2.101-0.654,3.214-0.606c-0.769-0.12-1.454-0.414-2.017-0.944c-1.077-1.016-1.472-2.267-1.082-3.693
                        c0.39-1.427,1.349-2.325,2.798-2.676c0.125-0.03,0.25-0.054,0.375-0.081C14.877,4.532,15.123,4.532,15.368,4.532z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M4.532,16.636c0.099-0.315,0.235-0.606,0.524-0.799
                        c0.401-0.267,0.825-0.307,1.254-0.089c0.436,0.223,0.667,0.595,0.672,1.084c0.009,0.817,0.004,1.633,0.002,2.449
                        c-0.002,0.628-0.439,1.134-1.059,1.23c-0.582,0.092-1.168-0.291-1.348-0.881c-0.014-0.045-0.031-0.089-0.046-0.133
                        C4.532,18.544,4.532,17.59,4.532,16.636z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"  d="M25.469,19.498c-0.1,0.315-0.236,0.607-0.525,0.798
                        c-0.4,0.267-0.824,0.308-1.254,0.089c-0.436-0.221-0.666-0.593-0.672-1.083c-0.01-0.81-0.004-1.619-0.002-2.429
                        c0-0.652,0.447-1.172,1.078-1.254c0.59-0.078,1.154,0.305,1.332,0.903c0.012,0.039,0.027,0.075,0.043,0.113
                        C25.469,17.59,25.469,18.544,25.469,19.498z"/>
                </g>
            </svg>
        </div>
        <div class="edu_dash_info">
            <ul>
                <li><p>Physical <span>{{$userData['books']->where('book_type', 'Physical')->count()}}</span></p></li>
                <li><p>E-Book <span>{{$userData['books']->where('book_type', 'E-Book')->count()}}</span></p></li>
            </ul>
        </div>
    </div>
    {{-- </a> --}}
</div>
<div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 col-12">
    {{-- <a href="{{ route($shared['routePath'].'.questionBankList')}}"> --}}
        <div class="edu_color_boxes box_right">
            <div class="edu_dash_box_data">
                <p>Total Questions</p>
                <h3>{{$userData['questions']->count()}}</h3>
            </div>
            <div class="edu_dash_box_icon">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 512 512" xml:space="preserve">
                    <g>
                        <g>
                            <g>
                                <path d="M248.158,343.22c-14.639,0-26.491,12.2-26.491,26.84c0,14.291,11.503,26.84,26.491,26.84
                                    c14.988,0,26.84-12.548,26.84-26.84C274.998,355.42,262.799,343.22,248.158,343.22z"/>
                                <path d="M252.69,140.002c-47.057,0-68.668,27.885-68.668,46.708c0,13.595,11.502,19.869,20.914,19.869
                                    c18.822,0,11.154-26.84,46.708-26.84c17.429,0,31.372,7.669,31.372,23.703c0,18.824-19.52,29.629-31.023,39.389
                                    c-10.108,8.714-23.354,23.006-23.354,52.983c0,18.125,4.879,23.354,19.171,23.354c17.08,0,20.565-7.668,20.565-14.291
                                    c0-18.126,0.35-28.583,19.521-43.571c9.411-7.32,39.04-31.023,39.04-63.789S297.307,140.002,252.69,140.002z"/>
                                <path d="M256,0C114.516,0,0,114.497,0,256v236c0,11.046,8.954,20,20,20h236c141.483,0,256-114.497,256-256
                                    C512,114.516,397.503,0,256,0z M256,472H40V256c0-119.377,96.607-216,216-216c119.377,0,216,96.607,216,216
                                    C472,375.377,375.393,472,256,472z"/>
                            </g>
                        </g>
                    </g>
                 </svg>
            </div>
            <div class="edu_dash_info">
                <ul>
                    <li><p>English <span>{{$userData['questions']->where('language_type', 'English')->count()}}</span></p></li>
                    <li><p>Hindi <span>{{$userData['questions']->where('language_type', 'Hindi')->count()}}</span></p></li>
                    
                    <li class="ml-2 mr-2 " style="border-left:2px solid rgb(240, 236, 236); height:20px;"><p>  &nbsp; Both <span>{{$userData['questions']->where('language_type', 'Both')->count()}}</span></p></li>
                    
                    <li class="ml-2 mr-2 " style="border-left:2px solid rgb(240, 236, 236); height:20px;"><p>  &nbsp; Not Imported <span>{{$userData['questions']->where('importStatus', 'Not Imported')->count()}}</span></p></li>

                    <li class="ml-2 mr-2 " style="border-left:2px solid rgb(240, 236, 236); height:20px;"><p>  &nbsp; Imported <span>{{$userData['questions']->where('importStatus', 'Imported')->count()}}</span></p></li>
                </ul>
            </div>
        </div>
    {{-- </a> --}}
</div>