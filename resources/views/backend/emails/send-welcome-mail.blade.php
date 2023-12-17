<!DOCTYPE html>
<html>
<head>
  <title></title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="{{ asset('assets/images/favicon1.png') }}" />
  <style type="text/css">
    /* FONTS */
    @import url('https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i');
    body,
    table,
    td,
    a {
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }

    table,
    td {
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
    }

    img {
      -ms-interpolation-mode: bicubic;
    }

    /* RESET STYLES */
    img {
      border: 0;
      height: auto;
      line-height: 100%;
      outline: none;
      text-decoration: none;
    }

    table {
      border-collapse: collapse !important;
    }

    body {
      height: 100% !important;
      margin: 0 !important;
      padding: 0 !important;
      width: 100% !important;
    }

    /* iOS BLUE LINKS */
    a[x-apple-data-detectors] {
      color: inherit !important;
      text-decoration: none !important;
      font-size: inherit !important;
      font-family: inherit !important;
      font-weight: inherit !important;
      line-height: inherit !important;
    }

    /* MOBILE STYLES */
    @media screen and (max-width:600px) {
      h1 {
        font-size: 32px !important;
        line-height: 32px !important;
      }
    }

    /* ANDROID CENTER FIX */
    div[style*="margin: 16px 0;"] {
      margin: 0 !important;
    }
  </style>
</head>

<body style="background-color: #f3f5f7; margin: 0 !important; padding: 0 !important;">
  <div style="display: none; font-size: 1px; color: #f3f5f7; line-height: 1px; font-family: 'Poppins', sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
    We're thrilled to have you here! Get ready to dive into your new account.
  </div>

  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- LOGO -->
    <tr>
      <td align="center">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <tr>
            <td align="center" valign="top" style="padding: 40px 10px 10px 10px;">
              <a href="#" target="_blank" style="text-decoration: none;">
                <img src="{{asset('assets/images/disa_testboard_logo.png')}}" alt="Logo" width="60" height="50"/>
              </a>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <!-- HERO -->
    <tr>
      <td align="center" style="padding: 0px 10px 0px 10px;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <tr>
            <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 10px 20px; border-radius: 4px 4px 0px 0px;">
              <h1 style="color: #000000; font-size: 23px; font-weight: 400; margin: 0; font-family: 'Poppins', sans-serif;">Disa Testboard - Login Credentials!</h1>
            </td>
          </tr>
        </table>
            </td>
    </tr>
    <tr>
      <td align="center" style="padding: 0px 10px 0px 10px;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <!-- COPY -->
          <tr>
            <td bgcolor="#ffffff" align="left" style="padding: 10px 30px 10px 30px; color: #000000; font-family: 'Poppins', sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; text-align: center;">
              <p style="margin: 0;"> Dear, {{$details['name']}} This mail is to inform you, that you are successfully registered with us. This email contains your User ID and password. We'll have you up and running in no time..</p>
              <hr>
              <label>User ID: {{$details['email']}}</label> <br>
              <label>Password: {{$details['password']}}</label>
            </td>
          </tr>
          <tr>
          <tr>
            <td bgcolor="#ffffff" align="left">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td bgcolor="#ffffff" align="center" style="padding: 20px 30px 30px 30px;">
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center" style="border-radius: 3px;" bgcolor="#46bc5c">
                            <a href="{{URL::to('/admin')}}" target="_blank" style="font-size: 18px; font-family: 'Poppins', sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: 12px 50px; border-radius: 5px; border: 1px solid #46bc5c; display: inline-block;">Login</a>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <!-- COPY -->
          <tr>
            <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 20px 30px; color: #000000; font-family: &apos;Lato&apos;, 'Poppins', sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
              <p style="margin: 0; text-align: center;">Please click button above to getting started with User Id & Password.</p>
            </td>
          </tr>
          <!-- COPY -->
          <tr>
            <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 40px 30px; border-radius: 0px 0px 0px 0px; color: #000000; font-family: 'Poppins', sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
              <p style="margin: 0;">Thanks & Regards!,<br>Team, Disa Testboard</p>
              <hr>
                <div>
                <p style="font-size:11px; font-style:italic">This is a system generated email, please don’t
                    reply this email. If you have not submitted this request kindly ignore
                    this email.</p>
                </div>
            </td>
          </tr>

        </table>
      </td>
    </tr>
    <!-- FOOTER -->
  </table>
</body>
</html>
