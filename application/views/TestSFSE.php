<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to SFSE</title>
	<script src="<?php echo base_url('bootstrap-3.0.3/dist/css/bootstrap.css');?>" ></script> 
	<script src="<?php echo base_url('bootstrap-3.0.3/examples/signin/signin.css');?>" ></script> 
</head>

<body>
<div class="container">
	<form class="form-signin" method="POST" action='<?php echo site_url("Test"); ?>'>
     <h2 class="form-signin-heading line">
      <span>ADD_JOB 在Activity里面增加一个job</span>
      </h2>
      <div class="control-group " id="groupemail">
			<label> key		<input class="input-block-level" type="text"		name="key"		value="ADD_JOB"		></label>
			<label> tid		<input class="input-block-level" type="text"		name="tid"		value="0">			</label>
			<label> name	<input class="input-block-level" type="text"		name="name"		value="HallActivity" ></label>
			<label> count	<input class="input-block-level" type="text"		name="count"	value="1">			</label>
			<label> vjid	<input class="input-block-level" type="text"		name="vjid"		value="2">			</label>
			<label> vc		<input class="input-block-level" type="text"		name="vc"		value="1">			</label>	
			<button class="btn btn-primary"  type="submit">发送</button>
      </div>

  </form>
	<form class="form-signin" method="POST" action='<?php echo site_url("Test"); ?>'>
     <h2 class="form-signin-heading line">
      <span>ADD_JOB 在Activity里面增加一个job</span>
	  </h2>
   <div class="control-group " id="groupemail">
			<label> key		<input class="input-block-level" type="text"		name="key"		value="INC_JOB"		></label>
			<label> tid		<input class="input-block-level" type="text"		name="tid"		value="0">			</label>
			<label> count	<input class="input-block-level" type="text"		name="count"	value="1">			</label>
			<label> jid 	<input class="input-block-level" type="text"		name="jid"		value="2">			</label>
			<label> vcount	<input class="input-block-level" type="text"		name="vcount"	value="2">			</label>	
			<button class="btn btn-primary"  type="submit">发送</button>
	  </div>

  </form>
	<form class="form-signin" method="POST" action='<?php echo site_url("Test"); ?>'>
     <h2 class="form-signin-heading line">
      <span>ADD_JOB 在Activity里面增加一个job</span>
	  </h2>
	  <div class="control-group " id="groupemail">
			<label> key		<input class="input-block-level" type="text"		name="key"		value="DEC_JOB"		></label>
			<label> tid		<input class="input-block-level" type="text"		name="tid"		value="0">			</label>
			<label> count	<input class="input-block-level" type="text"		name="count"	value="1">			</label>
			<label> jid 	<input class="input-block-level" type="text"		name="jid"		value="2">			</label>
			<label> vcount	<input class="input-block-level" type="text"		name="vcount"	value="1">			</label>	
			<button class="btn btn-primary"  type="submit">发送</button>
	  </div>


  </form>

	<form class="form-signin" method="POST" action='<?php echo site_url("Test"); ?>'>
     <h2 class="form-signin-heading line">
      <span>ADD_JOB 在Activity里面增加一个job</span>
	  </h2>
	  <div class="control-group " id="groupemail">
			<label> key		<input class="input-block-level" type="text"		name="key"		value="DOA_JOB"		></label>
			<label> tid		<input class="input-block-level" type="text"		name="tid"		value="0">			</label>
			<label> count	<input class="input-block-level" type="text"		name="count"	value="1">			</label>
			<label> jid 	<input class="input-block-level" type="text"		name="jid"		value="2">			</label>
			<label> vcount	<input class="input-block-level" type="text"		name="vcount"	value="1">			</label>	
			<button class="btn btn-primary"  type="submit">发送</button>
	  </div>


  </form>

	<form class="form-signin" method="POST" action='<?php echo site_url("Test"); ?>'>
     <h2 class="form-signin-heading line">
      <span>ADD_JOB 在Activity里面增加一个job</span>
	  </h2>
	  <div class="control-group " id="groupemail">
			<label> key		<input class="input-block-level" type="text"		name="key"		value="GET_FARM"		></label>
			<button class="btn btn-primary"  type="submit">发送</button>
	  </div>
  </form>

	<form class="form-signin" method="POST" action='<?php echo site_url("Test"); ?>'>
     <h2 class="form-signin-heading line">
      <span>ADD_JOB 在Activity里面增加一个job</span>
	  </h2>
	  <div class="control-group " id="groupemail">
			<label> key		<input class="input-block-level" type="text"		name="key"		value="DEL_JOB"		></label>
			<label> tid		<input class="input-block-level" type="text"		name="tid"		value="0">			</label>
			<label> jid 	<input class="input-block-level" type="text"		name="jid"		value="2">			</label>
			<button class="btn btn-primary"  type="submit">发送</button>
	  </div>
  </form>

	<form class="form-signin" method="POST" action='<?php echo site_url("Test"); ?>'>
     <h2 class="form-signin-heading line">
      <span>ADD_JOB 在Activity里面增加一个job</span>
	  </h2>
	  <div class="control-group " id="groupemail">
			<label> key		<input class="input-block-level" type="text"		name="key"		value="SPD_JOB"		></label>
			<label> tid		<input class="input-block-level" type="text"		name="tid"		value="0">			</label>
			<label> jid 	<input class="input-block-level" type="text"		name="jid"		value="2">			</label>
			<label> secs	<input class="input-block-level" type="text"		name="secs"		value="0">			</label>
			<label> prog	<input class="input-block-level" type="text"		name="prog"		value="2">			</label>
			<button class="btn btn-primary"  type="submit">发送</button>
	  </div>
  </form>


	<form class="form-signin" method="POST" action='<?php echo site_url("Test"); ?>'>
     <h2 class="form-signin-heading line">
      <span>ADD_JOB 在Activity里面增加一个job</span>
	  </h2>
	  <div class="control-group " id="groupemail">
			<label> key		<input class="input-block-level" type="text"		name="key"		value="LIT_JOB"		></label>
			<label> tid		<input class="input-block-level" type="text"		name="tid"		value="0">			</label>
			<label> jid 	<input class="input-block-level" type="text"		name="jid"		value="2">			</label>
			<button class="btn btn-primary"  type="submit">发送</button>
	  </div>
  </form>

</div>

</body>
</html>

