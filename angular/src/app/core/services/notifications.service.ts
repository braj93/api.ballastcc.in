import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

import { ApiService } from './api.service';
import { Notification } from '../models';
import { map } from 'rxjs/operators';
import * as moment from 'moment';
@Injectable()
export class NotificationsService {
  
  public notifications : any = [];
  public notification_guid: any;
  public idx: any;
  constructor (
    private apiService: ApiService
  ) {}

  getNotification(input): Observable<Notification> {
    return this.apiService.post('/notifications/list', input )
       .pipe(map((data) => data));
  }


  getNotifications(){
    this.getNotification({}).subscribe(
      (data:any) => {
        // console.log(data);
        // this.notifications = data;
        this.replaceStringInTemplate(data);
      },
      err => {
        console.log(err.message);
      }
    );
  }

  aTimeAgo(date){
    let ago = moment.utc(date).fromNow();
    return ago;
  }

  deleteNotification(input): Observable<Notification> {
    return this.apiService.post('/notifications/delete', input )
       .pipe(map((data) => data.data));
  }

  replaceStringInTemplate(notifications_data){
    this.notifications = [];
    // console.log(notifications_data);
    for (let obj of notifications_data) {
      if (obj.notification_type_key === 'welcome') {
        obj.notification_template = obj.notification_template;
      } else {
      let new_str = '';
      let notification_template_str = obj.notification_template
      for (let key in obj) {
        if (key === 'p1') {
          new_str = notification_template_str.replace('#p1#', obj[key][0].name);
        }
        if (key === 'p2') {
          new_str = new_str.replace('#p2#', obj[key][0].name);
        }
        if (key === 'p3') {
          new_str = new_str.replace('#p3#', obj[key][0].name);
        }
        if (key === 'p4') {
          new_str = new_str.replace('#p4#', obj[key][0].name);
        }
      }
        obj.notification_template = new_str;
      }
      this.notifications.push(obj);
    }

  }
}
