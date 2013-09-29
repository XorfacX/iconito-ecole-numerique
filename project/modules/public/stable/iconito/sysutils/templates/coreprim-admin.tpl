<h2>{$ppo->title}</h2>

<form action="{$ppo->action}" class="edit">
{foreach from=$ppo->classRoomsBySchools item=school}
    
    <div class="content-panel">
        <h4>
            <a href="" class="display-school accordion" data-school="{$school->id}">
                {$school->name} <em class="classrooms-list"></em>
            </a>
        </h4>
            
        <div class="classrooms" data-school="{$school->id}">
            <input type="checkbox" id="school-{$school->id}" class="school" data-name="{$ppo->activateForSchool}" data-school="{$school->id}" {if ($school->checked == $school->nbClassRooms) && ($school->checked != 0)}checked="checked"{/if}/>
            <label for="school-{$school->id}">{$ppo->activateForSchool}</label>
            <ul>
                {foreach from=$school->classRooms item=classroom}
                    <li>
                        <input type="checkbox" class="classroom" id="classroom-{$classroom->id}" name="classrooms[]" data-school="{$school->id}" data-name="{$classroom->name}" value="{$classroom->id}" {if $classroom->checked}checked="checked"{/if}/> 
                        <label for="classroom-{$classroom->id}">{$classroom->name}</label>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
{/foreach}
<div class="submit">
    <input type="submit" class="button button-confirm" />
</div>
</form>
{literal}
    <style type="text/css">
        .content-panel h4{
            margin: 5px;
        }
        
        .content-panel h4 .classrooms-list{
            font-weight: normal;
            color: #aaa;
        }
        
        .classrooms{
            margin-top: 1em;
            padding-left: 1em;
        }
        .classrooms ul{
            margin-top: 0.5em;
            padding-left: 1em;
            list-style: none;
        }
    </style>
    <script type="text/javascript">
        jQuery( document ).ready(function( $ ) {
            $('.classrooms').hide();
            
            $('.display-school').click(function(e){
               var id = $(this).data('school');
               $('.classrooms[data-school='+id+']').toggle();
               $(this).toggleClass('accordionOpen');
               e.preventDefault();
               e.stopPropagation();
               return false;
            });
            
            $('input.school').change(function(e){
                var id = $(this).data('school');
                var checked = $(this).prop('checked');
                
                $('.classroom[data-school='+id+']').prop('checked', function(i, val){
                    return checked;
                });
                
                makeClassRoomsList(id);
            });
            
            $('input.classroom').change(function(e){
               updateSchoolState($(this).data('school')); 
            });
            
            var updateSchoolState = function(schoolId){
                var nbCheckedClassroomsInSchool = $('.classroom[data-school='+schoolId+']:checked').length;
                var nbClassroomsInSchool = $('.classroom[data-school='+schoolId+']').length;
                
                var allSchoolIsChecked = (nbCheckedClassroomsInSchool == nbClassroomsInSchool && nbClassroomsInSchool != 0);
                    
                $('.school[data-school='+schoolId+']').prop('checked', allSchoolIsChecked);
                makeClassRoomsList(schoolId);
            };
            
            var makeClassRoomsList = function(schoolId){
                var listOfCheckedClassrooms = '';
                if($('.school[data-school='+schoolId+']').prop('checked')){
                    listOfCheckedClassrooms = $('.school[data-school='+schoolId+']').data('name');
                }else if($('.classroom[data-school='+schoolId+']:checked').length != 0){
                    var nameList = new Array();
                    $('.classroom[data-school='+schoolId+']:checked').each(function(item){
                        nameList.push($(this).data('name'));
                    });
                    listOfCheckedClassrooms = nameList.join(', ');
                    
                }else{
                    listOfCheckedClassrooms = '{/literal}{$ppo->noClassRooms}{literal}';
                }
                
                $('a[data-school='+schoolId+'] .classrooms-list').html('(' + listOfCheckedClassrooms + ')');
            };
            
            $('.classrooms').each(function(item){
                updateSchoolState($(this).data('school'));
            });
        });
    </script>
{/literal}