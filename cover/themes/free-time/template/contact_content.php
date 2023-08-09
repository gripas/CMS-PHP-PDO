
<h2><?php echo t("Contact Us"); ?></h2>
<form id="contact-form" method="post">
    <div class="form-group">
        <label for="name"><?php echo t("Name"); ?></label>
        <input type="text"  class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="email"><?php echo t("Email"); ?></label>
        <input type="email"  class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="message"><?php echo t("Message"); ?></label>
        <textarea  class="form-control" id="message" name="message" rows="4" required></textarea>
    </div>
    <div class="form-group">
    <label for="captcha"><?php echo t("Human Check"); ?>: <span id="captchaQuestion"></span></label>
    <div class="d-flex justify-content-between">
        <div id="possibleAnswers" class="d-flex flex-wrap"></div>
        <div id="chosenAnswer" class="mt-2 border rounded p-2 flex-grow-1 ml-3" style="height: 42px">Įtempti atsakymą čia</div>
    </div>
</div>


    <button  class="w-100 btn btn-lg btn-primary mb-3 mt-5" type="submit"><?php echo t("Send"); ?></button>
</form>
