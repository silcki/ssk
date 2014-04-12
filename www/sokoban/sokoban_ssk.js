function detect_key(the_key){
    if(document.all){
        key_code = window.event.keyCode;
    }
    else{
        the_key.preventDefault();
        key_code = the_key.which;
    }
    if((key_code==38)||(key_code==87)){
        direction = 1;
        move_player(0,-1);
    }
    if((key_code==37)||(key_code == 65)){
        direction = 2;
        move_player(-1,0)
    }
    if((key_code==39)||(key_code==68)){
        direction = 3;
        move_player(1,0)
    }
    if((key_code==40)||(key_code==83)){
        direction = 4;
        move_player(0,1);
    }
}

function move_player(x,y){
    if(game_solved==0){
        turns++;
        if((kira_y+y>-1)&&(kira_x+x>-1)&&(kira_y+y<num_rows)&&(kira_x+x<num_columns)){
            switch(game_field[kira_y+y][kira_x+x]){
                case  0:
                    game_field[kira_y][kira_x]-=100;
                    change_image(kira_y,kira_x);
                    kira_x += x;
                    kira_y += y;
                    game_field[kira_y][kira_x]+=100;
                    change_image(kira_y,kira_x);
                    break;
                case 10:
                    game_field[kira_y][kira_x]-=100;
                    change_image(kira_y,kira_x);
                    kira_x += x;
                    kira_y += y;
                    game_field[kira_y][kira_x]+=100;
                    change_image(kira_y,kira_x);
                    break;
                case 20:
                    if((game_field[kira_y+2*y][kira_x+2*x]!=1)&&(game_field[kira_y+2*y][kira_x+2*x]!=20)&&(game_field[kira_y+2*y][kira_x+2*x]!= 30)&&(kira_y+2*y >-1)&&(kira_x+2*x >-1)&&(kira_y+2*y <num_rows)&&(kira_x+2*x < num_columns)){
                        game_field[kira_y][kira_x]-=100;
                        change_image(kira_y,kira_x);
                        kira_x += x;
                        kira_y += y;
                        game_field[kira_y][kira_x]+=80;
                        change_image(kira_y,kira_x);
                        game_field[kira_y+y][kira_x+x]+=20;
                        change_image(kira_y+y,kira_x+x);
                        if(game_field[kira_y+y][kira_x+x]==30){
                            box_in_target++;
                        }
                    }
                    break;
                case 30:
                    if((game_field[kira_y+2*y][kira_x+2*x]!= 1)&&(game_field[kira_y+2*y][kira_x+2*x]!=20)&&(game_field[kira_y+2*y][kira_x+2*x]!= 30)&&(kira_y+2*y >-1)&&(kira_x+2*x >-1)&&(kira_y+2*y <num_rows)&&(kira_x+2*x<num_columns)){
                        game_field[kira_y][kira_x]-=100;
                        change_image(kira_y,kira_x);
                        kira_x += x;
                        kira_y += y;
                        game_field[kira_y][kira_x]+=80;
                        change_image(kira_y,kira_x);
                        game_field[kira_y+y][kira_x+x]+=20;
                        change_image(kira_y+y,kira_x+x);
                        if (game_field[kira_y+y][kira_x+x]!=30){
                            box_in_target--;
                        }
                    }
                    break;
            }
            $('div.sokoban_moves span').text(turns);
//            $('div.sokoban_total span').text(num_targets);
//            $('div.sokoban_target span').text(box_in_target);
            if(box_in_target == num_targets){
                game_solved=1;
            }
        }
    }
    if (game_solved==1){
        var level = $('a.sokoban_reset').data('level');
        $.ajax({
            url: '/ajax/sokobannext/',
            type:'POST',
            dataType:'json',
            data:{level:level},
            success: function(data) {
                $.fancybox(data.message);
            }
        });
    }
}

function change_image(y,x){
    var image_number='f'+y+'_'+x;
    switch (game_field[y][x]){
        case 0:
            assign_image(image_number,free_image);break;
        case 10:
            assign_image(image_number,target_image);break;
        case 20:
            assign_image(image_number,box_image);break;
        case 30:
            assign_image(image_number,box_in_target_image);break;
        case 100:
            switch(direction){
                case 1:
                    assign_image(image_number,kira_walking_up);
                    break;
                case 2:
                    assign_image(image_number,kira_walking_left);
                    break;
                case 3:
                    assign_image(image_number,kira_walking_right);
                    break;
                case 4:
                    assign_image(image_number,kira_walking_down);
                    break;
                default:
                    assign_image(image_number,player_image);
                    break;
            }
        default:
            if (game_field[y][x]>100){
                switch(direction){
                    case 1:
                        assign_image(image_number,kira_walking_up);
                        break;
                    case 2:
                        assign_image(image_number,kira_walking_left);
                        break;
                    case 3:
                        assign_image(image_number,kira_walking_right);
                        break;
                    case 4:
                        assign_image(image_number,kira_walking_down);
                        break;
                    default:
                        assign_image(image_number,player_image);
                        break;
                }
            }
            break;
    }
}

function assign_image(image_number,image_object){
    window.document.images[image_number].src = image_object.src;
}

function on_click_action(){
    alert("Используйте пожалуйста клавиши курсора ('стрелочки') для того, чтобы навести порядок на складе.");
}


function load_level(level){
    $.ajax({
        url: '/ajax/sokoban/',
        type:'POST',
        dataType:'json',
        data:{level:level},
        success: function(data) {
            $('div.sokoban_moves span').text(0);

            kira_x = data.kira_x;
            kira_y = data.kira_y;
            num_rows = data.num_rows;
            num_columns = data.num_columns;

            num_targets=0;
            direction=0;
            box_in_target=0;
            game_solved=0;
            turns = 0;

            $.each(data.map, function(x, obj) {
                game_field[x] = new Array();
                $.each(obj, function(y, val) {
                    game_field[x][y]=val;
                });
            });

            var field_map = '';

            for(x =0 ; x < data.num_rows ; x++){
                for(y = 0; y < data.num_columns; y++){
                    var item = game_field[x][y];
                    switch(item){
                        case 0:
                            field_map+='<img OnClick="on_click_action()" height="29px" width="29px" alt="floor" src="'+free_image.src+'" name="f'+x+'_'+y+'">';
                            break;
                        case 1:
                            field_map+='<img height="29px" width="29px" alt="wall" src="'+wall_image.src+'" name="f'+x+'_'+y+'">';
                            break;
                        case 10:
                            field_map+='<img height="29px" width="29px" alt="target" src="'+target_image.src+'" name="f'+x+'_'+y+'">';
                            num_targets++;
                            break;
                        case 20:
                            field_map+='<img OnClick="on_click_action()" height="29px" width="29px" alt="crate" src="'+box_image.src+'" name="f'+x+'_'+y+'">';
                            break;
                        case 30:
                            field_map+='<img height="29px" width="29px" alt="crate in target" src="'+box_in_target_image.src+'" name="f'+x+'_'+y+'">';
                            box_in_targets++;
                            break;
                        case 100:
                            field_map+='<img OnClick="on_click_action()" height="29px" width="29px" alt="kira" src="'+kira_image.src+'" name="f'+x+'_'+y+'">';
                            break;
                        case 110:
                            field_map+='<img OnClick="on_click_action()" height="29px" width="29px" alt="kira in target" src="'+kira_image.src+'" name="f'+x+'_'+y+'">';
                            num_targets++;
                            break;
                        default:
                            field_map+='<img height="29px" width="29px" alt="empty" src="'+free_image.src+'" name="f'+x+'_'+y+'">';
                            break;
                    }
                }
                field_map+='<br>';
            }

            $('#sokoban_field_map').html(field_map);
//            $('div.sokoban_total span').text(num_targets);
//            $('div.sokoban_target span').text(box_in_target);
        }
    });
}

var level;
var box_image=new Image;
box_image.src="/sokoban/box.gif";
var kira_image=new Image;
kira_image.src="/sokoban/walkdown.gif";
var kira_walking_right = new Image;
kira_walking_right.src="/sokoban/walkright.gif";
var kira_walking_left = new Image;
kira_walking_left.src="/sokoban/walkleft.gif";
var kira_walking_down = new Image;
kira_walking_down.src="/sokoban/walkdown.gif";
var kira_walking_up = new Image;
kira_walking_up.src="/sokoban/walkup.gif";
var wall_image =new Image;
wall_image.src="/sokoban/wall.gif";
var free_image=new Image;
free_image.src="/sokoban/free.gif";
var target_image=new Image;
target_image.src="/sokoban/target.gif";
var box_in_target_image=new Image;
box_in_target_image.src="/sokoban/box_in_target.gif";
var kira_x;
var kira_y;
var num_targets=0;
var direction=0;
var num_rows;
var num_columns;
var game_field=new Array();
var box_in_target=0;
var game_solved=0;
var turns=0;

$(document).ready(function(){
    $('#sokoban_reset .btn_send.sokoban_reset_ok').click(function(e){
        e.preventDefault();
        load_level($('a.sokoban_reset').data('level'));
        $.fancybox.close();
    });

    $('#sokoban_reset .btn_send.sokoban_reset_cancel').click(function(e){
        e.preventDefault();
        $.fancybox.close();
    });

    $('a.sokoban_new').live('click', function(e){
        e.preventDefault();
        $('a.sokoban_reset').data('level', 1);
        $.fancybox.close();
        load_level(1);
    });

    $('a.sokoban_next').live('click', function(e){
        e.preventDefault();
        var level = $('a.sokoban_reset').data('level');
        level++;
        $('a.sokoban_reset').data('level', level);
        $.fancybox.close();
        $('select[name="sokoban_levels"]').val(level);
        load_level(level);
    });

    $('select[name="sokoban_levels"]').change(function(e){
        var level = $(this).val();
        $('a.sokoban_reset').data('level', level);
        load_level(level);
    });

    $(".sokoban_reset").fancybox({
        'titlePosition' : 'none'
    });
});