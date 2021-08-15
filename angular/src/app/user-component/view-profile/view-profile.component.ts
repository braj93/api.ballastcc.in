import {Component, OnInit} from '@angular/core';
import { UserService } from '../../core';
import {ActivatedRoute} from '@angular/router';

@Component({
  selector: 'app-view-profile',
  templateUrl: './view-profile.component.html',
  styleUrls: ['./view-profile.component.css']
})
export class ViewProileComponent implements OnInit{
  public isSubmitting: boolean = false;
  public alerts: any = [];
  public user_detail: any;
  public currentUser: any;
  constructor(
      public userService: UserService,
      public route: ActivatedRoute,
    ){

  }

  ngOnInit(): void {
    this.userService.page_title = "Profile";
    this.userService.currentUser.subscribe((userData: any) => {
        // this.getUserDetail(userData.user_guid);
      }
    );
    this. currentUser = this.userService.getCurrentUser();
    if (this.currentUser.user_guid) {
      this.getUserDetail(this.currentUser.user_guid);
    }
  }

  getUserDetail(user_id){
    this.userService.getDetailByUserId({user_id: user_id}).subscribe((response: any) => {
      this.isSubmitting = true; 
      this.user_detail = response.data;
      this.isSubmitting = false;
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      this.isSubmitting = false;
    });
  }
}