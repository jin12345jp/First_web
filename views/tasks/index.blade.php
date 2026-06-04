   @extends('layouts.app')
   @section('content')
   <div class="container">
       <h1>タスク一覧</h1>
       @foreach($tasks as $task)
           <div class="card mb-3">
               <div class="card-body">
                   <h5>{{ $task->title }}</h5>
                   <p>{{ $task->description }}</p>
               </div>
           </div>
       @endforeach
   </div>
   @endsection