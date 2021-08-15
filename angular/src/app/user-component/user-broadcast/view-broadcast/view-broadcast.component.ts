import {Component, OnInit, ViewChild} from '@angular/core';
import { UserService, AdminService } from '../../../core';
import { FormGroup, FormBuilder, Validators} from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
@Component({
  selector: 'app-view-broadcast',
  templateUrl: './view-broadcast.component.html',
  styleUrls: ['./view-broadcast.component.css']
})
export class ViewBroadcastComponent implements OnInit{
  public detail : any = '';
  constructor(
  	public userService: UserService,
  	public adminService: AdminService,
    public fb: FormBuilder,
    public router: Router,
    public route: ActivatedRoute,
  	){
  }

  ngOnInit(): void {
    this.route.data.subscribe((data:any) => {
      let result = data['view_broadcast_detail'];
      this.detail = result.data;
      this.updateBroadcastStatus()
    });
  }

  updateBroadcastStatus(){
    console.log({ broadcast_sent_user_id: this.route.snapshot.paramMap.get('id')});
    this.adminService.updateBroadcastStatus({ broadcast_sent_user_id: this.route.snapshot.paramMap.get('id')}).subscribe((response: any) => {
      console.log(response);
    }, err => {
      console.log(err);
    });
  }
  ngOnDestroy(): void{
    this.userService.alerts = [];
  }
  
}