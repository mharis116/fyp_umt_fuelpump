<div id="nav" class="pt-2">
    <a href="{{route('dash')}}">
        <button class="btn btn-danger ml-3"  data-toggle="tooltip" data-placement="bottom" title="Exit">
             
    <svg width="15" height="15" viewBox="0 0 24 24"  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        </button>
    </a>
    <button class='btn btn-primary float-right mm ' data-toggle="tooltip" data-placement="bottom" title="Press F11" id='screen'>
    
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-maximize-2"><polyline points="15 3 21 3 21 9"></polyline><polyline points="9 21 3 21 3 15"></polyline><line x1="21" y1="3" x2="14" y2="10"></line><line x1="3" y1="21" x2="10" y2="14"></line></svg>

    </button>
    
    <button  class="btn btn-primary float-right mm" type="button" data-toggle="collapse" href="#cacl" role="button" aria-expanded="false" aria-controls="cacl">  
        <i class="fa fa-calculator "></i>
    </button>
    <button class='btn btn-primary ml-2 mm ' data-toggle="tooltip" data-placement="bottom" title="refresh page" id='refresh'>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
    </button>
</div>


<style>
 
    .mm{
        margin-right:15px;
    }

    .container {
        position: fixed;
        top: 20%;
        right: 5%;
        /* transform: translate(-50%, -50%); */
        background: #fff;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.2);
        border-radius: 10px;
        padding-bottom: 20px;
        padding-top: 20px;
        width: 320px;
        z-index:100;
        background-color: #5E5858
        
    }
    .display {
        width: 100%;
        height: 60px;
        /* padding: 40px 0; */
        background: #05ff8a;
    }
    .buttons {
        padding: 20px 20px 0 20px;
    }
    .rowc {
        width: 280px;
        float: left;
    }
    .button{
        width: 60px;
        height: 60px;
        float: left;
        padding: 2;
        margin: 5px;
        box-sizing: border-box;
        background: #404140;
        border: none;
        font-size: 30px;
        line-height: 30px;
        /* border-radius: 50%; */
        font-weight: 500;
        color: #faf4f4;
        cursor: pointer;
        box-shadow:0px 0px 5px #faf4f4;
    }
    .buttonb{
        width: 60px;
        height: 60px;
        float: left;
        padding: 2;
        margin: 5px;
        box-sizing: border-box;
        background: rgb(0, 195, 255);
        border: none;
        font-size: 30px;
        line-height: 30px;
        /* border-radius: 50%; */
        font-weight: 600;
        color: #faf4f4;
        cursor: pointer;
        box-shadow:0px 0px 5px #c6eec3;
        
    }
    .buttone{
        width: 60px;
        height: 60px;
        float: left;
        padding: 2;
        margin: 5px;
        box-sizing: border-box;
        background: rgb(0, 119, 255);
        border: none;
        font-size: 30px;
        line-height: 30px;
        /* border-radius: 50%; */
        font-weight: 600;
        color: #faf4f4;
        cursor: pointer;
        box-shadow:0px 0px 5px #9cf399;
        
    }
    .text {
        width: 270px;
        height: 60px;
        float: left;
        padding: 0;
        /* box-sizing: border-box; */
        border: none;
        background: none;
        color: #fff;
        text-align: right;
        font-weight: 500;
        font-size: 50px;
        line-height: 60px;
        margin: 0 25px;
        
    }
    .red {
        background: #1ba6dd !important;
        color: #ffffff !important;
        
    }
</style>
    <script>
        function calcNumbers(result){
            form.displayResult.value=form.displayResult.value+result;
        }
	</script>
	<div class="container collapse" id="cacl">
        
		<form name="form">
            <a class="btn btn-danger float-right mt-0" data-toggle="collapse" href="#cacl" role="button" aria-expanded="false" aria-controls="cacl">
                x
            </a>
            <br>
            <br>
		<div class="display">
			<input type="text" id="dis" class='text' placeholder="0" name="displayResult" />
		</div>
			<div class="buttons">
			    <div class="row rowc">
                    <input type="button" class="button"  name="b7" value="7" onClick="calcNumbers(b7.value)">
                    <input type="button" class="button" name="b8" value="8" onClick="calcNumbers(b8.value)">
                    <input type="button" class="button"  name="b9" value="9" onClick="calcNumbers(b9.value)">
				</div>
				
				<div class="row  rowc">
                    <input type="button" class="button"  name="b4" value="4" onClick="calcNumbers(b4.value)">
                    <input type="button" class="button"  name="b5" value="5" onClick="calcNumbers(b5.value)">
                    <input type="button" class="button"  name="b6" value="6" onClick="calcNumbers(b6.value)">
				</div>
				
				<div class="row  rowc">
                    <input type="button"  class="button" name="b1" value="1" onClick="calcNumbers(b1.value)">
                    <input type="button" class="button"  name="b2" value="2" onClick="calcNumbers(b2.value)">
                    <input type="button" class="button"  name="b3" value="3" onClick="calcNumbers(b3.value)">
				</div>
				
				<div class="row  rowc">
                    <input type="button" class="button"  name="b0" value="0" onClick="calcNumbers(b0.value)">
                    <input type="button"  class="button" name="potb" value="." onClick="calcNumbers(potb.value)">
                    <input type="button"  class="buttonb" name="cle" value="AC" onClick="document.getElementById('dis').value = null">
                    <input type="button" class="buttone" value="=" onClick="displayResult.value=eval(displayResult.value)">
				</div>
                <div class="row  rowc">
                    <input type="button" class="buttonb"  name="addb" value="+" onClick="calcNumbers(addb.value)">
                    <input type="button" class="buttonb"  name="subb" value="-" onClick="calcNumbers(subb.value)">
                    <input type="button" class="buttonb"  name="mulb" value="*" onClick="calcNumbers(mulb.value)">
                    <input type="button" class="buttonb"  name="divb" value="/" onClick="calcNumbers(divb.value)">
                </div>
			</div>
		
		</form>
	</div>