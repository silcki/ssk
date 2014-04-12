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
			if(box_in_target == num_targets){
				game_solved=1;
			}
		}
	}
	if (game_solved==1){
		alert('Hooooray! solved in '+turns+' moves!');
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
			if(box_in_target == num_targets){
				game_solved=1;
			}
		}
	}
	if (game_solved==1){
		alert('Hooooray! solved in '+turns+' moves!');
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


function load_level(){
	num_rows=11;
	num_columns=8;
	kira_x=2; 
	kira_y=6;
	for(var x=0;x<num_rows;x++){
		game_field[x] = new Array();
		for(var y=0;y<num_columns;y++){
			game_field[x][y]=0;
		}
	}
	game_field[1][1] = 1;
	game_field[1][2] = 1;
	game_field[1][3] = 1;
	game_field[1][4] = 1;
	game_field[2][1] = 1;
	game_field[2][4] = 1;
	game_field[3][0] = 1;
	game_field[3][1] = 1;
	game_field[3][4] = 1;
	game_field[3][5] = 1;
	game_field[4][0] = 1;
	game_field[4][2] = 10;
	game_field[4][3] = 20;
	game_field[4][4] = 10;
	game_field[4][5] = 1;
	game_field[4][6] = 1;
	game_field[4][7] = 1;
	game_field[5][0] = 1;
	game_field[5][2] = 20;
	game_field[5][4] = 20;
	game_field[5][7] = 1;
	game_field[6][0] = 1;
	game_field[6][1] = 1;
	game_field[6][2] = 110;
	game_field[6][3] = 20;
	game_field[6][4] = 10;
	game_field[6][7] = 1;
	game_field[7][1] = 1;
	game_field[7][2] = 1;
	game_field[7][5] = 1;
	game_field[7][6] = 1;
	game_field[7][7] = 1;
	game_field[8][2] = 1;
	game_field[8][3] = 1;
	game_field[8][4] = 1;
	game_field[8][5] = 1;
	for(var x=0;x<num_rows;x++){
		for(var y=0;y<num_columns;y++){
		
			var item = game_field[x][y];
			
			switch(item){
				case 0:	document.write('<img OnClick="on_click_action()" height="29px" width="29px" alt="floor" src="'+free_image.src+'" name="f'+x+'_'+y+'">');
						break;
				case 1:	document.write('<img height="29px" width="29px" alt="wall" src="'+wall_image.src+'" name="f'+x+'_'+y+'">');
						break;
				case 10:document.write('<img height="29px" width="29px" alt="target" src="'+target_image.src+'" name="f'+x+'_'+y+'">');
						num_targets++;
						break;
				case 20:document.write('<img OnClick="on_click_action()" height="29px" width="29px" alt="crate" src="'+box_image.src+'" name="f'+x+'_'+y+'">');
						break;
				case 30:document.write('<img height="29px" width="29px" alt="crate in target" src="'+box_in_target_image.src+'" name="f'+x+'_'+y+'">');
						box_in_targets++;
						break;
				case 100:document.write('<img OnClick="on_click_action()" height="29px" width="29px" alt="kira" src="'+kira_image.src+'" name="f'+x+'_'+y+'">');
						break;
				case 110:document.write('<img OnClick="on_click_action()" height="29px" width="29px" alt="kira in target" src="'+kira_image.src+'" name="f'+x+'_'+y+'">');
						num_targets++;
						break;							
				default:document.write('<img height="29px" width="29px" alt="empty" src="'+free_image.src+'" name="f'+x+'_'+y+'">');
						break;
			}
		}
		document.write('<br>');
	}
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
