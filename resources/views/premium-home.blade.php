 <div class="row">
	 <div class="col-md-12">
		 <div class="d-flex justify-content-between align-items-center breaking-news bg-white">
			 <marquee class="news-scroll" behavior="scroll" direction="left" onmouseover="this.stop();"
				 onmouseout="this.start();">
				 @foreach ($premium_domain as $premium_dom)
					 <span class="dot"></span> <a href="{{ $premium_dom->domain }}">{{ $premium_dom->domain }}</a>
				 @endforeach
			 </marquee>
		 </div>
	 </div>
 </div>
