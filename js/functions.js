// Login function
function login() {
    var e = $('#usrname').val()
    var p = $('#psw').val()
    var r = $('#checkbx').is(':checked')
    if (e == '' || p == '') {
        $('#loginstatus').html('<span style="color:red;"><b>Fill out all of the form data</b></span>').fadeOut(20000);
        return false;
    } else {
        $('#logbtn').css('display','none');
            //$("#loginstatus").html('please wait ...');
        $('#loginstatus').html('<span style="color:#004080;">Please wait...</span><img src="images/loading2.gif" height="30", width="30">');
        var xhttp
        if (window.XMLHttpRequest) {
            // code for modern browsers
            xhttp = new XMLHttpRequest()
        } else {
            // code for IE6, IE5
            xhttp = new ActiveXObject('Microsoft.XMLHTTP')
        }
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText == 'login_failed') {
                    $('#loginstatus').html('<span style="color:red;"><b>You did not provide either email or password!</b></span>').fadeOut(20000);
                    $('#logbtn').css('display','block');
                    return false;
                } else if(this.responseText == 'invalid') {
                  $('#loginstatus').html('<span style="color:red;"><b>You are yet to activate your account, or you provided a wrong password!</b></span>').fadeOut(20000);
                  $('#logbtn').css('display','block');
                  return false;
                } else {
                    window.location = 'user.php?u=' + this.responseText
                }
            }
        }
        xhttp.open('POST', 'index.php', true)
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
        xhttp.send('e=' + e + '&p=' + p + '&r=' + r)
    }
}

// To bring up the login modal
$(document).ready(function(){
  $("#myBtn").click(function(){
    $("#myModal").modal();
  });
});
// To bring up the registration modal and hide the login modal
$(document).ready(function(){
  $("#regBtn").click(function(){
    $("#regModal").modal();
    $("#myModal").modal('hide');
  });
});
// To bring up the forgot password modal and hide the login modal
$(document).ready(function(){
  $("#forgotpassword").click(function(){
    $("#forgotModal").modal();
    $("#myModal").modal('hide');
  });
});
// To bring up the registration modal from the register button on the landing page
$(document).ready(function(){
  $("#userReg").click(function(){
    $("#regModal").modal();
    $("#myModal").modal('hide');
  });
});

// Function for user registration
function signup(){
    var e = $("#signupusrname").val();
    var p1 = $("#signuppsw").val();
    var p2 = $("#signuppsw1").val();
    var status = $("#regstatus");
    if(e == "" || p1 == "" || p2 == ""){
        status.html('<span style="color:red;"><b>Fill out all of the form data</b></span>').fadeOut(20000);
        return false;
    } else if(p1 != p2){
        status.html('<span style="color:red;"><b>Your password fields do not match</b></span>').fadeOut(20000);
        return false;
    } else if(!document.getElementById("checkbox_id").checked){
        status.html('<span style="color:red;"><b>You must accept the terms and condition to register</b></span>').fadeOut(20000);
        return false;
    } else {
        $("#signupbtn").css('display','none');
        status.html('Please wait...<img src="images/loading2.gif" height="30", width="30">');
        var xhttp
        if (window.XMLHttpRequest) {
            // code for modern browsers
            xhttp = new XMLHttpRequest()
        } else {
            // code for IE6, IE5
            xhttp = new ActiveXObject('Microsoft.XMLHTTP')
        }
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText != 'signup_success') {
                    status.html(ajax.responseText).fadeOut(20000);
                    $("#signupbtn").css('display', 'block');
                    return false;
                } else {
                    $("#signupbtn").css('display', 'none');
                    //window.scrollTo(0,0);
                    status.html("<h3>OK, check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You need to do this within 24 hours. You will not be able to do anything on the site until you successfully activate your account.</h3>");
                }
            }
        }
        xhttp.open('POST', 'signup.php', true)
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
        xhttp.send('e=' + e + '&p=' + p1)
    }
}

function forgotpwd() {
    var e = $('#forgotmail').val();
    if (e == '') {
        $('#forgotstatus').html('Type in your email address').fadeOut(20000);
    } else {
        $('#forgotbtn').css('display', 'none');
        $('#forgotstatus').html('Please wait...<img src="images/loading2.gif" height="30", width="30">');
        var xhttp
        if (window.XMLHttpRequest) {
            // code for modern browsers
            xhttp = new XMLHttpRequest()
        } else {
            // code for IE6, IE5
            xhttp = new ActiveXObject('Microsoft.XMLHTTP')
        }
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText == 'success') {
                    $('#forgotstatus').html('<h3>Check your email inbox in a few minutes</h3>').fadeOut(50000);
                } else if (this.responseText == 'no_exist') {
                    $('#forgotstatus').html('Sorry that email address is not in our system').fadeOut(20000);
                } else if (this.responseText == 'email_send_failed') {
                    $('#forgotstatus').html('Mail function failed to execute').fadeOut(20000);
                } else {
                    $('#forgotstatus').html('An unknown error occurred, please check the email you supplied!').fadeOut(20000);
                }
            }
        }
        xhttp.open('POST', 'forgot_pass.php', true)
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
        xhttp.send('e=' + e)
    }
}

$(document).ready(function() {
  $('#submitbtn').click(function(event) {
    event.preventDefault()
    var bzname = $("#buzname").val();
    var bzaddr = $("#buzAdd").val();
    var bzcontact = $("#buzContact").val();
    var mobile = $("#mobile").val();
    var product = $("input[name='product']:checked").val();
    var service = $("input[name='service']:checked").val();
    var comment = $("#comment").val();
    var username = $("#logusername").val();
    var fsrmail = $("#fsremail").val();
    var website = $("#website").val();
    var mail = $("#email").val();
    if (bzname == '' || bzaddr == '' || bzcontact == '' || mobile == '' || comment == '') {
        $('#submitstatus').html('Please fill the required fields').fadeOut(20000);
        return false;
    } else {
        $('#submitbtn').css('display', 'none');
        $('#submitstatus').html('Please wait...<img src="images/loading2.gif" height="30", width="30">');
        var xhttp
        if (window.XMLHttpRequest) {
            // code for modern browsers
            xhttp = new XMLHttpRequest()
        } else {
            // code for IE6, IE5
            xhttp = new ActiveXObject('Microsoft.XMLHTTP')
        }
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText == 'success') {
                    $('#submitstatus').html('<h3>Congratulations on your business registration. You are one step away from being approved. Now take a look at the different <a href="billing.php?u=' + username + '"> packages</a> and subscribe to the one that suites your need. Please note that we won\'t be able to approve your business until you subscribe to a package. Welcome once again.</h3>');
                } else {
                    $('#submitstatus').html('An unknown error occurred, please try again later.').fadeOut(20000);
                }
            }
        }
        xhttp.open('POST', 'functions/business_registration.php', true)
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
        xhttp.send('bzname=' + bzname + '&bzaddr=' + bzaddr + '&bzcontact=' + bzcontact + '&mobile=' + mobile + '&website=' + website + '&mail=' + mail + '&fsrmail=' + fsrmail + '&product=' + product + '&service=' + service + '&comment=' + comment)
      }
  })
})

function buzList(id) {
  username = id.split('_')[0]
  agentUsername = id.split('_')[1]
  $.ajax({
      url: 'agenthomefeed.php',
      type: 'POST',
      data: { username: username, agentUsername: agentUsername },
      success: function(data) {
          //output = $.parseHTML(result),
              $('#agenthomefeed').html(data)
      }
  })
}

function fsrFeed(id) {
  bizusername = id.split('_')[0]
  $.ajax({
      url: 'saleshomefeed.php',
      type: 'POST',
      data: { bizusername: bizusername },
      success: function(data) {
          //output = $.parseHTML(result),
              $('#saleshomefeed').html(data)
      }
  })
}
// Load the middle page of agent on page load
$(document).ready(function() {
  $('#agenthomefeed').html('Please wait...<img src="images/loading2.gif" height="30", width="30">');
    $.ajax({
        type: 'POST',
        url: 'agenthomefeedstatic.php',
        success: function(data) {
            $('#agenthomefeed').html(data)
        }
    })
})

// Load the middle page of fsr on page load
$(document).ready(function() {
  $('#saleshomefeed').html('Please wait...<img src="images/loading2.gif" height="30", width="30">');
    $.ajax({
        type: 'POST',
        url: 'fsrhomefeedstatic.php',
        success: function(data) {
            $('#saleshomefeed').html(data)
        }
    })
})

// Show the business counts for fsr
$(document).ready(function() {
    $.ajax({
        type: 'POST',
        url: 'functions/fsrbusinesscount.php',
        success: function(data) {
            $('#fsrbadge').html(data)
        }
    })
})

function agentnail(id) {
  id = id.split('_')[0]
  $.ajax({
      url: 'agentthumbnail.php',
      type: 'POST',
      data: { id: id },
      success: function(data) {
          //output = $.parseHTML(result),
              $('#agentthumbnail').html(data)
      }
  })
}

function showAgentComments(id) {
  $(document).ready(function() {
    bizusername = id.split('_')[0]
    agentUsername = id.split('_')[1]
    var comment = document.getElementById('agentComment')
    if (comment) {
        comment.innerHTML = '<div style="text-align:center; margin-top:20px;"><img src="images/loading2.gif" height="30", width="30"></div>'
    }
    $.ajax({
        url: 'functions/showagentcomments.php',
        type: 'POST',
        data: { bizusername: bizusername, agentUsername: agentUsername },
        success: function(data) {
            $('#agentComment').html(data)
      }
    })
  })
}

function showfsrBizComments(id) {
  $(document).ready(function() {
    bizusername = id.split('_')[0]
    logusername = id.split('_')[1]
    var comment = document.getElementById('fsragentComment')
    if (comment) {
        comment.innerHTML = '<div style="text-align:center; margin-top:20px;"><img src="images/loading2.gif" height="30", width="30"></div>'
    }
    $.ajax({
        url: 'functions/showfsrbizcomments.php',
        type: 'POST',
        data: { bizusername: bizusername, logusername: logusername },
        success: function(data) {
            $('#fsragentComment').html(data)
      }
    })
  })
}

function agentPost() {
  $(document).ready(function(){
    var mobile = $("#mobile1").val();
    var mail = $("#mail1").val();
    var name = $("#name1").val();
    var location = $("#location1").val();
    var keyword = $("#keyword1").val();
    var comment = $("#comment1").val();
    var bzusername = $("#bzusername").val();
    var agentUsername = $("#agentUsername").val();
    var smsUpdate = $('#smsupdate').is(':checked');
    var emailUpdate = $('#emailupdate').is(':checked');
    var phoneUpdate = $('#phoneupdate').is(':checked');
    var telegramUpdate = $('#telegramupdate').is(':checked');
    if (mobile == '' || comment == '') {
        $('#submitstatus').html('Please fill the required fields').fadeOut(20000);
        return false;
    } else {
        $('#postbtn1').css('display', 'none');
        $('#submitstatus').html('Please wait...<img src="images/loading2.gif" height="30", width="30">');
    //}
    $.ajax({
      url: 'functions/agent_comment.php',
      type: 'POST',
      data: { bzusername: bzusername, comment: comment, agentUsername: agentUsername, mobile: mobile, mail: mail, name: name, location: location, keyword: keyword, smsUpdate: smsUpdate, emailUpdate: emailUpdate, phoneUpdate: phoneUpdate, telegramUpdate: telegramUpdate },
      success: function(data) {
        $('#postbtn1').css('display', 'block')
        $('#submitstatus').remove()
        $('#agentComment').prepend(data)
        $("#mobile1").val('')
        $("#mail1").val('')
        $("#name1").val('')
        $("#location1").val('')
        $("#keyword1").val('')
        $("#comment1").val('')
        $("#smsupdate").prop("checked", false)
        $("#emailupdate").prop("checked", false)
        $("#phoneupdate").prop("checked", false)
        $("#telegramupdate").prop("checked", false)
        }
      })
    }
  })
}

// SMS leads update
function smsPost() {
  $(document).ready(function(){
    var mobile = $("#mobile1").val();
    var comment = $("#comment1").val();
    //var bzusername = $("#bzusername").val();
    var bzmobile = $("#bzmobile").val();
    $.ajax({
      url: 'functions/smsleadUpdate.php',
      type: 'POST',
      data: { bzmobile: bzmobile, comment: comment, mobile: mobile },
      })
  })
}

// Telegram leads update
function telegramPost() {
  $(document).ready(function(){
    var mobile = $("#mobile1").val();
    var comment = $("#comment1").val();
    var bzmobile = $("#bzmobile").val();
    $.ajax({
      url: 'functions/telegramPostUpdate.php',
      type: 'POST',
      data: { bzmobile: bzmobile, comment: comment, mobile: mobile },
      })
  })
}

// Script to load more agent feeds
var i = 1;
function loadagentfeed() {
  $(document).ready(function(){
    var rows = Number($('#row').val())
    var allfeeds = Number($('#allfeeds').val())
    var buzusername = $('#buzusername').val()
    var agentusername = $('#agentusername').val()
    var rowperpage = 50
    var count = document.getElementById('inc').value = ++i;
    row = rows + rowperpage

    if (row <= allfeeds) {
        $('#row').val(row)
        $('.load-feed').html('Loading...')
        $.ajax({
            url: 'functions/getmoreAgentFeed.php',
            type: 'POST',
            data: { row: row, buzusername: buzusername, agentusername: agentusername, count: count },
            success: function(response) {
                // appending posts after last post
                if(response != '') {
                $('.load-feed').remove()
                $('#agentComment').append(response)
              }
            }
        })
    }
  })
}

function edit(but) {
  // get parent and then first child <div>
  var div0 = but.parentNode.parentNode.childNodes[0].nextSibling
  var ih = div0.innerHTML // record the text of div
  //alert(ih)
  div0.innerHTML = "<input type='text' />" // insert an input
  div0.firstElementChild.value = ih // set input value

  // now get buttons and change visibility
  but.style.visibility = 'hidden' // edit button
  but.parentNode.nextSibling.firstChild.style.visibility = 'visible'
}

function editusertype(but) {
    // get parent and then first child <div>
    var div0 = but.parentNode.parentNode.childNodes[0].nextSibling
    var ih = div0.innerHTML // record the text of div
    //alert(ih)
    //div0.innerHTML = "<input type='text' />" // insert an input
    div0.innerHTML = "<select><option value='superadmin'>Super Admin</option><option value='admin'>Admin</option><option value='manager'>Manager</option><option value='supervisor'>Supervisor</option><option value='user' selected>User</option><option value='agent'>Agent</option><option value='sales'>FSR</option><option value='billing'>Billing</option><option value='support'>Support</option></select>"

    div0.firstElementChild.value = ih // set input value

    // now get buttons and change visibility
    but.style.visibility = 'hidden' // edit button
    but.parentNode.nextSibling.firstChild.style.visibility = 'visible'
}

function save(but) {
    // get parent and then first child <div>
    var div0 = but.parentNode.parentNode.childNodes[0].nextSibling
    update_value(div0.id, div0.firstElementChild.value) // send id and new text to ajax function

    // now Restore back to normal mode
    div0.innerHTML = div0.firstElementChild.value
    but.parentNode.previousSibling.firstElementChild.style.visibility = 'visible'
    but.style.visibility = 'hidden'
}

function update_value(id, value) {
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.open('GET', 'functions/profile_page_func.php?id=' + id + '&value=' + value + '&status=Save', true)
    xhttp.send(null)
        // console.log(status);
}

function useBizName(id) {
  $(document).ready(function(){
      status = id.split('_')[1]
      profile_id = id.split('_')[2]
      $.ajax({
          url: 'functions/usebizname.php',
          type: 'POST',
          data: { status: status, profile_id: profile_id }
      })
  });
}

function smsUpdate(id) {
  $(document).ready(function(){
      status = id.split('_')[1]
      profile_id = id.split('_')[2]
      $.ajax({
          url: 'functions/smsupdate.php',
          type: 'POST',
          data: { status: status, profile_id: profile_id }
      })
  });
}

function telegramUpdate(id) {
  $(document).ready(function(){
      status = id.split('_')[1]
      profile_id = id.split('_')[2]
      $.ajax({
          url: 'functions/telegramUpdate.php',
          type: 'POST',
          data: { status: status, profile_id: profile_id },
          success: function(response) {
              if(response == 'success') {
              alert('Please install Telegram app on your mobile phone. Search for CallNect, open it, click on start. Please do this right away!');
            }
          }
      })
  });
}

function tagImage(id) {
  $(document).ready(function(){
      status = id.split('_')[1]
      profile_id = id.split('_')[2]
      $.ajax({
          url: 'functions/tagimage.php',
          type: 'POST',
          data: { status: status, profile_id: profile_id }
      })
  });
}

function enable(but) {
  $(document).ready(function(){
    var id = but.parentNode.previousSibling.id
    username = id.split('_')[1]
    $.ajax({
        url: 'functions/telegramEnabled.php',
        type: 'POST',
        data: { username: username },
    })
  });
}

// Function for searching users
function key_down_users(e) {
    if (e.keyCode === 13) {
        e.preventDefault()
        searchUsers()
    }
}

// This function sends search query to the search page
function searchUsers() {
  $(document).ready(function() {
    var searchquery = document.getElementById('searchquery').value
    $.ajax({
        url: 'functions/manageuser_display.php',
        type: 'POST',
        data: { searchquery: searchquery },
        success: function(response) {
            // appending posts after last post
            if(response != '') {
            //$('.load-feed').remove()
            $('#usermgt').html(response)
          }
        }
    })
  })
}

// Function for searching users
function key_down_fsr(e) {
    if (e.keyCode === 13) {
        e.preventDefault()
        searchFSR()
    }
}
// This function search for the fsr
function searchFSR() {
  $(document).ready(function() {
    var searchfsr = document.getElementById('searchfsr').value
    $.ajax({
        url: 'functions/managefsr_display.php',
        type: 'POST',
        data: { searchfsr: searchfsr },
        success: function(response) {
            // appending posts after last post
            if(response != '') {
            //$('.load-feed').remove()
            $('#fsrmgt').html(response)
          }
        }
    })
  })
}

// Function for searching users
function key_down_agent(e) {
    if (e.keyCode === 13) {
        e.preventDefault()
        searchagent()
    }
}
// This function search for the fsr
function searchagent() {
  $(document).ready(function() {
    var searchagent = document.getElementById('searchagent').value
    $.ajax({
        url: 'functions/manageagent_display.php',
        type: 'POST',
        data: { searchagent: searchagent },
        success: function(response) {
            // appending posts after last post
            if(response != '') {
            //$('.load-feed').remove()
            $('#agentmgt').html(response)
          }
        }
    })
  })
}

// Function for searching users
function key_down_agent_biz_alloc(e) {
    if (e.keyCode === 13) {
        e.preventDefault()
        searchforBizagents()
    }
}
// This function search for the fsr
function searchforBizagents() {
  $(document).ready(function() {
    var searchagent = document.getElementById('searchforBizagents').value
    $.ajax({
        url: 'functions/agenttobusinessalloc_display.php',
        type: 'POST',
        data: { searchagent: searchagent },
        success: function(response) {
            // appending posts after last post
            if(response != '') {
            //$('.load-feed').remove()
            $('#agentbizsearch').html(response)
          }
        }
    })
  })
}

// Function to unsuspend a user
function activate(id) {
  $(document).ready(function(){
    var conf = confirm('Are you sure you want to activate this user?')
    if (conf != true) {
        return false;
    }
      status = id.split('_')[1]
      profile_id = id.split('_')[2]
      $.ajax({
          url: 'functions/suspenduser.php',
          type: 'POST',
          data: { status: status, profile_id: profile_id }
      })
  });
}
// Function to suspend a user
function deactivate(id) {
  $(document).ready(function(){
    var conf = confirm('Are you sure you want to deactivate this user? If you do so, the user won\'t be able to access this platform anymore until you activate again. Do you want to continue?')
    if (conf != true) {
        return false;
    }
      status = id.split('_')[1]
      profile_id = id.split('_')[2]
      $.ajax({
          url: 'functions/suspenduser.php',
          type: 'POST',
          data: { status: status, profile_id: profile_id }
      })
  });
}

function approve(id) {
  $(document).ready(function(){
    var conf = confirm('Are you sure you want to approve this business? Double check again if the business has agent, fsr and callnect number assigned. Also check if the business has subscribed to a package.')
    if (conf != true) {
        return false;
    }
    status = id.split('_')[1]
    profile_id = id.split('_')[2]
    $.ajax({
        url: 'functions/bizapprove.php',
        type: 'POST',
        data: { status: status, profile_id: profile_id },
        success: function(response) {
            if (response == "already_approved") {
                alert('You cannot approve a business twice! This business is already approved.')
            } else if(response == 'empty_details') {
                alert('You need to provide agentusername, fsrusername and callnect number.')
            } else if(response == 'success') {
              alert('Successfully approved.')
            }
        }
    })
  });
}

function disapprove(id) {
  $(document).ready(function(){
    var conf = confirm('Are you sure you want to disapprove this business? Doing so will delete the agent, fsr and callnect number assigned to this business. Do you want to continue?')
    if (conf != true) {
        return false;
    }
    status = id.split('_')[1]
    profile_id = id.split('_')[2]
    $.ajax({
        url: 'functions/bizdisapprove.php',
        type: 'POST',
        data: { status: status, profile_id: profile_id }
    })
  });
}

$(document).ready(function() {
  $('#searchbillingbtn').click(function(event) {
    event.preventDefault()
    var date = $('#searchbilling').val()
    if(date == '') {
      alert('Please select a date')
      return false;
    }
    $.ajax({
        url: 'functions/subscription_search.php',
        type: 'POST',
        data: { date: date },
        success: function(response) {
          if(response != '') {
            $('#billingsearch').html(response)
          }
        }
    })
  })
});

$(document).ready(function() {
  $('#fsrevenuebtn').click(function(event) {
    event.stopImmediatePropagation()
    var date = $('input[name=fsrevenue]').val()
    var username = $('input[name=fsrhidden]').val()
    if(date == '') {
      alert('Please select a date')
      return false;
    }
    $.ajax({
        url: 'functions/fsrevenue_search.php',
        type: 'POST',
        data: { date: date, username: username },
        success: function(response) {
          if(response != '') {
            $('#fsrmonthlyrev').html(response)
          }
        }
    })
  })
});

$(document).ready(function() {
  $('#fsrmonthlybtn').click(function(event) {
    event.stopImmediatePropagation()
    var date = $('#fsrmonthly').val()
    var username = $('#fsrusername').val()
    if(date == '') {
      alert('Please select a date')
      return false;
    } else if(username == '') {
      alert('Please provide the fsr username')
      return false;
    }
    $.ajax({
        url: 'functions/fsrevenue_search.php',
        type: 'POST',
        data: { date: date, username: username },
        success: function(response) {
          if(response != '') {
            $('#fsrmonthlysearch').html(response)
          }
        }
    })
  })
});

$(document).ready(function() {
  $('input.datepicker').Zebra_DatePicker();
});

$(document).ready(function() {
  $('input.datepickerfsr').Zebra_DatePicker();
});

$(document).ready(function() {
  $('input.datepickeradm').Zebra_DatePicker();
});

$(document).ready(function() {
  if(document.URL.indexOf('managebilling.php') != -1) {
    $("#managebilling").addClass('active');
  } else if(document.URL.indexOf('user.php') != -1) {
    $("#manageuser").addClass('active');
  } else if(document.URL.indexOf('managefsr.php') != -1) {
    $("#managefsr").addClass('active');
  } else if(document.URL.indexOf('manageagent.php') != -1) {
    $("#manageagent").addClass('active');
  } else if(document.URL.indexOf('managebusiness.php') != -1) {
    $("#managebuz").addClass('active');
  } else if(document.URL.indexOf('managenumber.php') != -1) {
    $("#managenum").addClass('active');
  } else if(document.URL.indexOf('agenttobusinessalloc.php') != -1) {
    $("#agentbuzalloc").addClass('active');
  } else if(document.URL.indexOf('smscampaign.php') != -1) {
    $("#smscamp").addClass('active');
  }
})

// Script for menu-bar tab selection
$(document).ready(function() {
    if ((document.URL.indexOf('user.php') != -1) || (document.URL.indexOf('manageuser.php') != -1) || (document.URL.indexOf('managebilling.php') != -1) || (document.URL.indexOf('managefsr.php') != -1) || (document.URL.indexOf('manageagent.php') != -1) || (document.URL.indexOf('managebusiness.php') != -1)) {
        $('#homeid').css('color', 'white')
    } else if ((document.URL.indexOf('profile') != -1) || (document.URL.indexOf('billing') != -1)) {
        $('#settingsid').css('color', 'white')
    } else if (document.URL.indexOf('notification') != -1) {
        $('#note_still').css('color', 'white')
    }
})


$(document).on("click", '#myScrollspy li', function(){
  $('#myScrollspy li').removeClass('active');
  $(this).addClass('active');
});

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});


// Function for searching business on landing page
function key_down_searchbiz(e) {
    if (e.keyCode === 13) {
        e.preventDefault()
        searchbusiness()
    }
}

// This function is for searching business on the landing page
function searchbusiness() {
  $(document).ready(function() {
    var searchquery = document.getElementById('searchbiz').value
    window.location = 'searchbusiness.php?searchquery=' + searchquery
  })
}

function chatAgent(id) {
  $(document).ready(function() {
    var alias = id
    window.location = 'chat.php?query=' + alias
  })
}

// Function for searching business on admin page
function key_down_bizsearch(e) {
    if (e.keyCode === 13) {
        e.preventDefault()
        searchbiz()
    }
}

// This function is for searching business on admin page
function searchbiz() {
  $(document).ready(function() {
    var searchquery = $('#bizsearch').val()
    //window.location = 'bizsearch.php?searchquery=' + searchquery
    if(searchquery == '') {
      alert('Please enter your search term')
      return false;
    }
    $.ajax({
        url: 'functions/bizsearch.php',
        type: 'POST',
        data: { searchquery: searchquery },
        success: function(response) {
          if(response != '') {
            $('#bizownertab').html(response)
          }
        }
    })
  })
}

// Function for searching business on admin page
function key_down_searchImg(e) {
    if (e.keyCode === 13) {
        e.preventDefault()
        searchImage()
    }
}

// This function is for searching business on admin page
function searchImage() {
  $(document).ready(function() {
    var searchquery = $('#bizImage').val()
    var alias = $('#imageInput').val()
    //window.location = 'bizsearch.php?searchquery=' + searchquery
    if(searchquery == '') {
      alert('Please enter your search term')
      return false;
    }
    $.ajax({
        url: 'functions/bizImageSearch.php',
        type: 'POST',
        data: { searchquery: searchquery, alias: alias },
        success: function(response) {
          if(response != '') {
            $('#imageRow').html(response)
          }
        }
    })
  })
}

$(document).ready(function() {
  $('#emaillinkbtn').click(function(event) {
    event.stopImmediatePropagation()
    var email = $('#emaillink').val()
    var username = $('#emaillinkhidden').val()
    if(email == '') {
      alert('Please enter the email')
      return false;
    }
    $.ajax({
        url: 'functions/emaillink.php',
        type: 'POST',
        data: { email: email, username: username },
        success: function(response) {
          if(response.indexOf('sucess') > -1) {
            $('#emaillinkstatus').html('<span style="color:forestgreen;"><b>Email successfully sent.</b></span>')
          }
        }
    })
  })
});

$(document).ready(function() {
  $('#salesbizregbtn').click(function(event) {
    event.preventDefault()
    var e = $("#signupusrname").val();
    var p1 = $("#signuppsw").val();
    var p2 = $("#signuppsw1").val();
    var bzname = $("#buzname").val();
    var bzaddr = $("#buzAdd").val();
    var bzcontact = $("#buzContact").val();
    var mobile = $("#mobile").val();
    var product = $("input[name='product']:checked").val();
    var service = $("input[name='service']:checked").val();
    var comment = $("#comment").val();
    var fsrmail = $("#fsremail").val();
    var website = $("#website").val();
    var mail = $("#email").val();
    if (bzname == '' || bzaddr == '' || bzcontact == '' || mobile == '' || comment == '') {
        $('#salesbizregstatus').html('Please fill the required fields').fadeOut(20000);
        return false;
    } else if(p1 != p2) {
      $('#salesbizregstatus').html('<span style="color:red;"><b>Your password fields do not match</b></span>').fadeOut(20000);
      return false;
    } else if(!document.getElementById("checkbox_id").checked){
        $('#salesbizregstatus').html('<span style="color:red;"><b>You must accept the terms and condition to register</b></span>').fadeOut(20000);
        return false;
      } else {
        $('#salesbizregbtn').css('display', 'none');
        $('#salesbizregstatus').html('Please wait...<img src="images/loading2.gif" height="30", width="30">');
        var xhttp
        if (window.XMLHttpRequest) {
            // code for modern browsers
            xhttp = new XMLHttpRequest()
        } else {
            // code for IE6, IE5
            xhttp = new ActiveXObject('Microsoft.XMLHTTP')
        }
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText == 'success') {
                    $('#salesbizregstatus').html("<h3>OK, check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the registration process by activating your account. You need to do this within 24 hours. You will not be able to do anything on the site until you successfully activate your account.</h3>");
                } else {
                    $('#salesbizregstatus').html('An unknown error occurred, please try again later.').fadeOut(20000);
                }
            }
        }
        xhttp.open('POST', 'saleslinkbizreg.php', true)
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
        xhttp.send('e=' + e + '&p=' + p1 + '&bzname=' + bzname + '&bzaddr=' + bzaddr + '&bzcontact=' + bzcontact + '&mobile=' + mobile + '&website=' + website + '&mail=' + mail + '&fsrmail=' + fsrmail + '&product=' + product + '&service=' + service + '&comment=' + comment)
      }
  })
})

$(document).ready(function() {
  $('#user_note_still').click(function() {
    $('#userbadge').remove()
  $.ajax({
      url: 'functions/zero-Counter.php',
      type: 'POST',
      success: function() {
        // something here...
      }
    })
  })
})

$(document).ready(function() {
  $('#admin_note_still').click(function() {
    $('#adminbadge').remove()
  $.ajax({
      url: 'functions/zero-Counter.php',
      type: 'POST',
      success: function() {
        // something here...
      }
    })
  })
})

$(document).ready(function() {
  $('#sales_note_still').click(function() {
    $('#salesbadge').remove()
  $.ajax({
      url: 'functions/zero-Counter.php',
      type: 'POST',
      success: function() {
        // something here...
      }
    })
  })
})

function deleteImg(id) {
  $(document).ready(function(){
    var conf = confirm('Are you sure you want to delete this picture?')
    if (conf != true) {
        return false;
    }
    var alias = $('#imageInput').val();
    var picture_id = id.split('_')[1]
    $.ajax({
        url: 'functions/delete_user_home_pix.php',
        type: 'POST',
        data: { picture_id: picture_id },
        success: function() {
            //if (responseText == alias) {
                window.location = 'chat.php?query=' + alias
            //}
        }
    })
  });
}

function editdesc(but) {
  var div0 = but.parentNode.childNodes[1] // the description
  var id = but.parentNode.childNodes[0].firstElementChild.nextSibling.id // id
  var ih = div0.innerHTML

  div0.innerHTML = "<textarea type='text' rows='1' style='width:100%;'/>" // insert an input
  div0.firstElementChild.value = ih // set input value

  // now get buttons and change visibility
  but.style.visibility = 'hidden' // edit button
  but.nextSibling.style.visibility = 'visible'
}

function savedesc(but) {
  var div0 = but.parentNode.childNodes[1] // the description
  var id = but.parentNode.childNodes[0].firstElementChild.nextSibling.id // id
  update_desc(id, div0.firstElementChild.value) // send this to ajax

  // now Restore back to normal mode
    div0.innerHTML = div0.firstElementChild.value
    but.previousSibling.style.visibility = 'visible'
    but.style.visibility = 'hidden'
}

function update_desc(id, value) {
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.open('GET', 'functions/profile_page_func.php?id=' + id + '&value=' + value + '&status=Save', true)
    xhttp.send(null)
        // console.log(status);
}

/*
$(document).ready(function() {
  $('#calcBtn').click(function() {
    if(!$("input[name='optradio']").is(':checked') && $("input[name='ivr']").is(':checked')) {
      alert('You cannot select IVR Language option without any selected plan!')
      return false;
    }
    if($('#platinum').is(':checked') && $("input[name='chat']").is(':checked')) {
      alert('The Platinum plan already has Live Chat as a feature. You don\'t need to select Live chat with Platinum')
      return false;
    }
    var plan = $("input[name='optradio']:checked").val();
    var ivr = $("input[name='ivr']:checked").val();
    var chat = $("input[name='chat']:checked").val();
    var ophour = $("input[name='ophour']:checked").val();
    var duration = $("input[name='duration']:checked").val();
    var username = $("#logusername").val();
    $.ajax({
        url: 'functions/calculateTotal.php',
        type: 'POST',
        data: { plan: plan, ivr: ivr, chat: chat, ophour: ophour, duration: duration, username: username },
        success: function(response) {
          if(response != '') {
            $('#totalprice').html(response)
            $('#total').val(response)
            if(response != '0') {
            $('#payNow').attr('type', 'image');
            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
          } else if(response == '0') {
            alert('You need to select either a plan or Live Chat option.')
          }
          }
        }
    })
  })
}) */

$(document).ready(function() {
  $('#bronze').click(function() {
    var plan = $("input[name='optradio']:checked").val();
    document.getElementById('total').value = plan
    var username = $("#logusername").val();
    document.getElementById('merchant_ref').value = username + '_' + plan
  })
})

$(document).ready(function() {
  $('#silver').click(function() {
    var plan = $("input[name='optradio']:checked").val();
    document.getElementById('total').value = plan
    var username = $("#logusername").val();
    document.getElementById('merchant_ref').value = username + '_' + plan
  })
})

$(document).ready(function() {
  $('#gold').click(function() {
    var plan = $("input[name='optradio']:checked").val();
    document.getElementById('total').value = plan
    var username = $("#logusername").val();
    document.getElementById('merchant_ref').value = username + '_' + plan
  })
})

$(document).ready(function() {
  $('#platinum').click(function() {
    var plan = $("input[name='optradio']:checked").val();
    document.getElementById('total').value = plan
    var username = $("#logusername").val();
    document.getElementById('merchant_ref').value = username + '_' + plan
  })
})
/*
// This function sets the value of merchant_ref input field
$(document).ready(function() {
  $('#payNow').click(function() {
    var plan = $("input[name='optradio']:checked").val();
    var username = $("#logusername").val();
    document.getElementById('merchant_ref').value = username + '_' + plan
  })
})
*/
// This function removes comma from the total amount before sending to voguepay
function filterNum() {
  var re = /,/g;
  var numonly = document.getElementById('total').value.replace(re, "")
  document.getElementById('total').value = numonly
  document.getElementById('memo').value = 'CallNect-NG' + numonly
}

function optionalServices(id) {
  $(document).ready(function() {
  var checked = $('#' + id).prop('checked') ? 1 : 0
  service = id.split('_')[0]
  username = id.split('_')[1]
  dataString = 'checked=' + checked + '&service=' + service + '&username=' + username
  $.ajax({
      type: 'POST',
      url: 'functions/optionalservices.php',
      data: dataString
    })
  })
}

function delagentbiz(id) {
  $(document).ready(function() {
    var agentid = id
    $.ajax({
        type: 'POST',
        url: 'functions/delagentbiz.php',
        data: { agentid: agentid},
        success: function(response) {
            if (response == "success") {
                alert('Agent successfully deleted. Refresh the page now.')
                $(".agtdelbtn").attr('disabled', 'disabled');
            } else if(response == 'not_success') {
                alert('Agent delete failure. Try again later')
            }
        }
      })
  })
}

$(document).ready(function() {
  $('#assigntbtn').click(function(event) {
    event.stopImmediatePropagation()
    var bzusername = $('#bzusername').val()
    var agentusername = $('#agtusername').val()
    if(bzusername == '') {
      alert('Please provide the business username')
      return false;
    } else if(agentusername == '') {
      alert('Please provide the agent username')
      return false;
    }
    $.ajax({
        url: 'functions/agent_to_business_alloc.php',
        type: 'POST',
        data: { bzusername: bzusername, agentusername: agentusername },
        success: function(response) {
          if(response == 'record_exist') {
            $('#agentbusinessalloc').html('Agent ' + agentusername + ' is already allocated to business ' + bzusername)
          } else if(response == 'biz_not_exist') {
            $('#agentbusinessalloc').html('The business username supplied does not exist!')
          } else if(response == 'agent_not_exist'){
            $('#agentbusinessalloc').html('The agent username supplied does not exist!')
          } else if(response == 'failed') {
            $('#agentbusinessalloc').html('Agent to business allocation failed!')
          } else if(response == 'success') {
            $('#agentbusinessalloc').html('Agent ' + agentusername + ' successfully assigned to business ' + bzusername)
            $('#assigntbtn').attr('disabled', 'disabled')
          }
        }
    })
  })
});

function openSearch() {
  document.getElementById("myOverlay").style.display = "block";
}

function closeSearch() {
  document.getElementById("myOverlay").style.display = "none";
}

$(document).ready(function() {
  $('#smscampaign1').click(function(event) {
    event.preventDefault()
    var mobile = $('#mobile').val()
    var text = $('#text1').val()
    if(mobile == '' || text == '') {
      alert('The fields cannot be empty please!')
      return false;
    }
    $('#smscampaign1').prop('disabled', true)
    $('#camp1').html('Please wait...<img src="images/loading2.gif" height="30", width="30">');
    $.ajax({
        url: 'functions/smscampaign1.php',
        type: 'POST',
        data: { mobile: mobile, text: text },
        success: function(response) {
          if(response == 'success') {
            $("#mobile").val('')
            $("#text1").val('')
            $('#camp1').text('Campaign successfully started.')
            $('#smscampaign1').prop('disabled', false)
          }
        }
    })
  })
});

$(document).ready(function() {
    $('#smscampaign2').click(function(event) {
        // stop submit the form, we will post it manually.
        event.preventDefault()
            // Get form
        var form = $('#smsPostForm')[0]
            // Create an FormData object
        var data = new FormData(form)
            // disabled the submit button
        $('#smscampaign2').prop('disabled', true)
          $('#camp1').html('Please wait...<img src="images/loading2.gif" height="30", width="30">');
        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: 'functions/smscampaign2.php',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(data) {
                $('#camp2').text(data)
                $('#smscampaign2').prop('disabled', false)
            },
            error: function(e) {
                $('#camp2').text(e.responseText)
                    // console.log("ERROR : ", e);
                $('#smscampaign2').prop('disabled', false)
            }
        })
        var form = document.getElementById('smsPostForm')
        form.reset()
    })
})
