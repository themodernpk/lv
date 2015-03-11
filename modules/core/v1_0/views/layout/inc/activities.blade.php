<h4>{{$title}}</h4>
<table class="table table-striped table-hover table-bordered small">
@foreach ($items as $item)
    <tr>
        <td>#{{$item->id}}</td>
     <td>
         @if(!is_null($item->label))
             <span class="label" style="background-color: {{Common::stringToColorCode($item->label)}};">{{$item->label}}</span>
         @endif
         {{$item->content}}
     </td>
     <td>
         {{ Dates::showTimeAgo($item->created_at) }}
         @if(!is_null($item->user_id))
            <em>by <a href="{{URL::route('profile', array('id' => $item->user_id) )}}">{{User::find($item->user_id)->name}}</a> </em>
         @endif
     </td>
    </tr>
 @endforeach
</table>

