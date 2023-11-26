<style>

	#container_for_update_bcv{
		position: fixed;
		z-index: 9999;
		bottom: 3rem;
		left: -12rem;
		transition: .3s left;
	}
	#container_for_update_bcv:hover{
		left: .5rem;
	}
	#container_for_update_bcv:hover label{
		margin-left: -5rem;
		opacity: 0;
		transition: .3s margin-left, 0s opacity .3s;


	}
	#container_for_update_bcv label{
		
		font-size: 2rem;
		text-align: center;
		width: 4rem;
		background-color: #f0f0f0;
		border: 2px solid black;
		border-radius: 0 10px 10px 0;
		margin: 0;
		z-index: 9;
		opacity: 1;
		transition: .3s margin-left, .3s opacity;
	}

	#container_for_update_bcv button{
		padding: 1rem;
		width: 12rem;
		z-index: 10;
		transition: .1s transform;
	}
	#container_for_update_bcv button:active{
		transform: translateY(5px);
	}
	#for_update_bcv{
		display: flex;
	}
	
</style>

<div id="container_for_update_bcv">
	<div id="for_update_bcv">
		<button onclick="load_bcv()">ACTUALIZAR</button>
		<label>$</label>
	</div>
</div>