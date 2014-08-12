function Sokoban () {
    var self = this;

    this.box_image = new Image;
    this.kira_image = new Image;
    this.kira_walking_right = new Image;
    this.kira_walking_left = new Image;
    this.kira_walking_down = new Image;
    this.kira_walking_up = new Image;
    this.wall_image = new Image;
    this.free_image = new Image;
    this.target_image = new Image;
    this.box_in_target_image = new Image;

    this.kira_x = 0;
    this.kira_y = 0;
    this.num_targets=0;
    this.direction=0;
    this.num_rows = 0;
    this.num_columns = 0;
    this.game_field=new Array();
    this.box_in_target=0;
    this.game_solved=0;
    this.turns=0;
    
    this.init = function() {
        self.box_image.src="/sokoban/box.gif";
        self.kira_image.src="/sokoban/walkdown.gif";
        self.kira_walking_right.src="/sokoban/walkright.gif";
        self.kira_walking_left.src="/sokoban/walkleft.gif";
        self.kira_walking_down.src="/sokoban/walkdown.gif";
        self.kira_walking_up.src="/sokoban/walkup.gif";
        self.wall_image.src="/sokoban/wall.gif";
        self.free_image.src="/sokoban/free.gif";
        self.target_image.src="/sokoban/target.gif";
        self.box_in_target_image.src="/sokoban/box_in_target.gif";
    }

    this.detect_key = function (the_key){
        if(document.all){
            key_code = window.event.keyCode;
        }
        else{
            the_key.preventDefault();
            key_code = the_key.which;
        }
        if((key_code==38)||(key_code==87)){
            self.direction = 1;
            self.move_player(0,-1);
        }
        if((key_code==37)||(key_code == 65)){
            self.direction = 2;
            self.move_player(-1,0)
        }
        if((key_code==39)||(key_code==68)){
            self.direction = 3;
            self.move_player(1,0)
        }
        if((key_code==40)||(key_code==83)){
            self.direction = 4;
            self.move_player(0,1);
        }
    }

    this.move_player = function(x,y){
        if(self.game_solved==0){
            self.turns++;
            if((self.kira_y+y>-1)&&(self.kira_x+x>-1)&&(self.kira_y+y<self.num_rows)&&(self.kira_x+x<self.num_columns)){
                switch(self.game_field[self.kira_y+y][self.kira_x+x]){
                    case  0:
                        self.game_field[self.kira_y][self.kira_x]-=100;
                        self.change_image(self.kira_y,self.kira_x);
                        self.kira_x += x;
                        self.kira_y += y;
                        self.game_field[self.kira_y][self.kira_x]+=100;
                        self.change_image(self.kira_y,self.kira_x);
                        break;
                    case 10:
                        self.game_field[self.kira_y][self.kira_x]-=100;
                        self.change_image(self.kira_y,self.kira_x);
                        self.kira_x += x;
                        self.kira_y += y;
                        self.game_field[self.kira_y][self.kira_x]+=100;
                        self.change_image(self.kira_y,self.kira_x);
                        break;
                    case 20:
                        if((self.game_field[self.kira_y+2*y][self.kira_x+2*x]!=1)&&(self.game_field[self.kira_y+2*y][self.kira_x+2*x]!=20)&&(self.game_field[self.kira_y+2*y][self.kira_x+2*x]!= 30)&&(self.kira_y+2*y >-1)&&(self.kira_x+2*x >-1)&&(self.kira_y+2*y <self.num_rows)&&(self.kira_x+2*x < self.num_columns)){
                            self.game_field[self.kira_y][self.kira_x]-=100;
                            self.change_image(self.kira_y,self.kira_x);
                            self.kira_x += x;
                            self.kira_y += y;
                            self.game_field[self.kira_y][self.kira_x]+=80;
                            self.change_image(self.kira_y,self.kira_x);
                            self.game_field[self.kira_y+y][self.kira_x+x]+=20;
                            self.change_image(self.kira_y+y,self.kira_x+x);
                            if(self.game_field[self.kira_y+y][self.kira_x+x]==30){
                                self.box_in_target++;
                            }
                        }
                        break;
                    case 30:
                        if((self.game_field[self.kira_y+2*y][self.kira_x+2*x]!= 1)&&(self.game_field[self.kira_y+2*y][self.kira_x+2*x]!=20)&&(self.game_field[self.kira_y+2*y][self.kira_x+2*x]!= 30)&&(self.kira_y+2*y >-1)&&(self.kira_x+2*x >-1)&&(self.kira_y+2*y <self.num_rows)&&(self.kira_x+2*x<self.num_columns)){
                            self.game_field[self.kira_y][self.kira_x]-=100;
                            self.change_image(self.kira_y,self.kira_x);
                            self.kira_x += x;
                            self.kira_y += y;
                            self.game_field[self.kira_y][self.kira_x]+=80;
                            self.change_image(self.kira_y,self.kira_x);
                            self.game_field[self.kira_y+y][self.kira_x+x]+=20;
                            self.change_image(self.kira_y+y,self.kira_x+x);
                            if (self.game_field[self.kira_y+y][self.kira_x+x]!=30){
                                self.box_in_target--;
                            }
                        }
                        break;
                }
                $('div.sokoban_moves span').text(self.turns);
                if(self.box_in_target == self.num_targets){
                    self.game_solved=1;
                }
            }
        }
        if (self.game_solved==1){
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

    this.change_image = function(y,x){
        var image_number='f'+y+'_'+x;
        switch (self.game_field[y][x]){
            case 0:
                self.assign_image(image_number,self.free_image);break;
            case 10:
                self.assign_image(image_number,self.target_image);break;
            case 20:
                self.assign_image(image_number,self.box_image);break;
            case 30:
                self.assign_image(image_number,self.box_in_target_image);break;
            case 100:
                switch(self.direction){
                    case 1:
                        self.assign_image(image_number,self.kira_walking_up);
                        break;
                    case 2:
                        self.assign_image(image_number,self.kira_walking_left);
                        break;
                    case 3:
                        self.assign_image(image_number,self.kira_walking_right);
                        break;
                    case 4:
                        self.assign_image(image_number,self.kira_walking_down);
                        break;
                    default:
                        self.assign_image(image_number,player_image);
                        break;
                }
            default:
                if (self.game_field[y][x]>100){
                    switch(self.direction){
                        case 1:
                            self.assign_image(image_number,self.kira_walking_up);
                            break;
                        case 2:
                            self.assign_image(image_number,self.kira_walking_left);
                            break;
                        case 3:
                            self.assign_image(image_number,self.kira_walking_right);
                            break;
                        case 4:
                            self.assign_image(image_number,self.kira_walking_down);
                            break;
                        default:
                            self.assign_image(image_number,player_image);
                            break;
                    }
                }
                break;
        }
    }

    this.assign_image = function(image_number,image_object){
        window.document.images[image_number].src = image_object.src;
    }

    this.load_level = function(level){
        $.ajax({
            url: '/ajax/sokoban/',
            type:'POST',
            dataType:'json',
            data:{level:level},
            success: function(data) {
                $('div.sokoban_moves span').text(0);

                self.kira_x = data.kira_x;
                self.kira_y = data.kira_y;
                self.num_rows = data.num_rows;
                self.num_columns = data.num_columns;

                self.num_targets=0;
                self.direction=0;
                self.box_in_target=0;
                self.game_solved=0;
                self.turns = 0;

                $.each(data.map, function(x, obj) {
                    self.game_field[x] = new Array();
                    $.each(obj, function(y, val) {
                        self.game_field[x][y]=val;
                    });
                });

                var field_map = '';

                for(x =0 ; x < data.num_rows ; x++){
                    for(y = 0; y < data.num_columns; y++){
                        var item = self.game_field[x][y];
                        switch(item){
                            case 0:
                                field_map+='<img OnClick="on_click_action()" height="29px" width="29px" alt="floor" src="'+self.free_image.src+'" name="f'+x+'_'+y+'">';
                                break;
                            case 1:
                                field_map+='<img height="29px" width="29px" alt="wall" src="'+self.wall_image.src+'" name="f'+x+'_'+y+'">';
                                break;
                            case 10:
                                field_map+='<img height="29px" width="29px" alt="target" src="'+self.target_image.src+'" name="f'+x+'_'+y+'">';
                                self.num_targets++;
                                break;
                            case 20:
                                field_map+='<img OnClick="on_click_action()" height="29px" width="29px" alt="crate" src="'+self.box_image.src+'" name="f'+x+'_'+y+'">';
                                break;
                            case 30:
                                field_map+='<img height="29px" width="29px" alt="crate in target" src="'+self.box_in_target_image.src+'" name="f'+x+'_'+y+'">';
                                self.box_in_targets++;
                                break;
                            case 100:
                                field_map+='<img OnClick="on_click_action()" height="29px" width="29px" alt="kira" src="'+self.kira_image.src+'" name="f'+x+'_'+y+'">';
                                break;
                            case 110:
                                field_map+='<img OnClick="on_click_action()" height="29px" width="29px" alt="kira in target" src="'+self.kira_image.src+'" name="f'+x+'_'+y+'">';
                                self.num_targets++;
                                break;
                            default:
                                field_map+='<img height="29px" width="29px" alt="empty" src="'+self.free_image.src+'" name="f'+x+'_'+y+'">';
                                break;
                        }
                    }
                    field_map+='<br>';
                }

                $('#sokoban_field_map').html(field_map);
            }
        });
    }
}

on_click_action = function(){
    alert("Используйте пожалуйста клавиши курсора ('стрелочки') для того, чтобы навести порядок на складе.");
}

var level;
$(document).ready(function(){
    $('#sokoban_reset .btn_send.sokoban_reset_ok').click(function(e){
        e.preventDefault();
        sokobanGame.load_level($('a.sokoban_reset').data('level'));
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
        sokobanGame.load_level(1);
    });

    $('a.sokoban_next').live('click', function(e){
        e.preventDefault();
        var level = $('a.sokoban_reset').data('level');
        level++;
        $('a.sokoban_reset').data('level', level);
        $.fancybox.close();
        $('select[name="sokoban_levels"]').val(level);
        sokobanGame.load_level(level);
    });

    $('a.sokoban_start').live('click', function(e){
        e.preventDefault();
        document.onkeydown = sokobanGame.detect_key;
        $(this).hide();
        $('a.sokoban_finish').show();
    });

    $('a.sokoban_finish').live('click', function(e){
        e.preventDefault();
        $(this).hide();
        $('a.sokoban_start').show();
        document.onkeydown = null;
    });

    $('select[name="sokoban_levels"]').change(function(e){
        var level = $(this).val();
        $('a.sokoban_reset').data('level', level);
        sokobanGame.load_level(level);
    });

    $(".sokoban_reset").fancybox({
        'titlePosition' : 'none'
    });
});