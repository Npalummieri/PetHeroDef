<?php include_once("header.php"); ?>
<?php include_once("nav.php"); ?>


<section class="py-5 h-100" style="background-color: #f8f9fa;">
<h2 class="text-center">CHAT ROOM</h2>
  <div class="container">
    <div id="select-container"></div>

    <div class="row">
      <div class="col-md-6 col-lg-5 col-xl-4 mb-4 mb-md-0 border border-rounded border-dark" style="background-color: lightblue;">
        <h5 class="font-weight-bold mb-3 text-center text-lg-start">Contacts</h5>
        <div class="card">

          <ul class="list-unstyled mb-0">
            <li class="p-2 border-bottom border-secondary">
            
              <div class="card-body" id="colAvail"></div>
                
            </li>
          </ul>
        </div>
      </div>

      <div class="col-md-6 col-lg-7 col-xl-8 border border-rounded border-dark" style="background-color: lightblue;">
        <div class="chat-box">
          <ul class="list-unstyled scrollable overflow-auto" style="height:500px; max-height: 750px;" id="chatDef"></ul>
            
          <div class="message-input bg-white mb-3 " id="messageSection" style="display: none;">
            <div class="form-outline">
              <textarea class="form-control border-rounded" id="msg" rows="4" placeholder="Type your message here..."></textarea>
            </div>
            <button type="button" class="btnSend btn btn-info btn-rounded float-end" id="sendMsg">Send</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?php echo JS_PATH . "formScripts.js" ?>"></script>
<script>
  $(document).ready(function () {
    chatModule.displayAvailToTalk();
    chatModule.selectChats();
    chatModule.sendMsg();
  });
</script>
<?php require_once("footer.php"); ?>