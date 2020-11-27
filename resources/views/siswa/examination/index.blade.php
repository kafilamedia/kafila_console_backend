@extends('layouts.master')

@section('head_script')
	<script src="{{ asset('js/axios.min.js') }}"></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML'></script>
	<script>
		// window.onbeforeunload = function (e) {
		// 	event.returnValue = 'Please finish the examination.';
		// }

		window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

		// MathJax.Hub.Queue(["Typeset",MathJax.Hub,"konten-soal"]);
		// MathJax.Hub.Queue(["Typeset",MathJax.Hub,"area-jawaban"]);
	</script>
@endsection

@section('extra_style')
	<style>
		.option-letter {
			padding: 10px 20px;
			margin-right: 15px;
			font-weight: bold;
			border: solid 1px;
			border-radius: 50px;
			cursor: pointer;
		}

		.letter-selected {
			background-color: #3298dc;
		}

		.letter-selected-not-sure {
			background-color: #ffdd57;
		}

		.pager-number-container {
			width: 50px;
			height: 50px;
			margin: 10px 15px 10px 0;
			position: relative;
		}

		.pager-number-answer {
			border-radius: 25px;
			position: absolute;
			right: 0;
			text-align: center;
			width: 25px;
			height: 25px;
			border: solid 1px;
			background: #f14668;
		}

		.pager-number-answer.not-empty {
			background: #3298dc;
		}

		.pager-number-answer.not-empty-ragu {
			background: #ffdd57;
		}

		.pager-number-item {
			font-weight: bold;
			font-size: 18px;
			margin: 10px 10px 0 0;
			border: solid 1px;
			width: 40px;
			height: 40px;
			background: #FFF;
			cursor: pointer;
		}

		.clearfix::after { 
		   content: " ";
		   display: block; 
		   height: 0; 
		   clear: both;
		   *zoom: expression( this.runtimeStyle['zoom'] = '1', this.innerHTML += '<div class="ie7-clear"></div>' );
		}
	</style>
@endsection

@section('content')
	{{-- <h1 class="title">Examination</h1> --}}

	@livewire('examination.pager', compact('page', 'pages', 'submisi'))

	<div class="box">
		@livewire('examination.soal', compact('page', 'question', 'submisi'))
	</div>

	@livewire('examination.navigation', compact('pages', 'submisi'))
@endsection

@section('extra_script')
	<script>
		function format_timer(number) {
      var sec_num = parseInt(number, 10); // don't forget the second param
      var hours   = Math.floor(sec_num / 3600);
      var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
      var seconds = sec_num - (hours * 3600) - (minutes * 60);

      if (hours   < 10) {hours   = "0"+hours;}
      if (minutes < 10) {minutes = "0"+minutes;}
      if (seconds < 10) {seconds = "0"+seconds;}
      return hours+':'+minutes+':'+seconds;
    }

    // handle timer (if no remain time end)
		var remain = parseInt('{{ $submisi->sisa_waktu }}');
		setInterval(function () {
			remain -= 1;
			document.getElementById('timer').innerHTML = format_timer(remain);

			if (remain <= 0) {
				// console.log('gila')
				axios.post('/siswa/finish-examination')
					.then(function(res) {
						console.log(res.data);
						window.location = '/siswa/finish-examination';
					})
					.catch(function(err) {
						console.log(err)
					});
			}
		}, 1000);

		// pager - alpinejs
    function handlePager(){
    	return {
    		show: false,
        open() { this.show = true },
        close() { this.show = false },
        isOpen() { return this.show === true },
    	};
    }

    // soal - alpinejs
    function handleSoal(){
    	return {
    		answerDirty: false,
    		answerFocus() { this.answerDirty = true },
    		isAnswerNotDirty() { return this.answerDirty === false },
    		answerSaved() { this.answerDirty = false }
    	};
    }

		// livewire hooks
		document.addEventListener("livewire:load", function(event) {
      window.livewire.hook('afterDomUpdate', () => {
          // Add your custom JavaScript here.
          MathJax.Hub.Queue(["Typeset",MathJax.Hub,"konten-soal"]);
					MathJax.Hub.Queue(["Typeset",MathJax.Hub,"area-jawaban"]);
      });
  	});
	</script>
@endsection