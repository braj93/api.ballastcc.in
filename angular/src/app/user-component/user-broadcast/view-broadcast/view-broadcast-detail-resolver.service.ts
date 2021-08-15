import { Injectable, } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, Router, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { AdminService } from '../../../core';
import { catchError } from 'rxjs/operators';

@Injectable()
export class ViewBroadcastDetailResolver implements Resolve<any> {
  constructor(
    private adminService: AdminService,
    private router: Router
  ) {}
  resolve(
    route: ActivatedRouteSnapshot, 
    state: RouterStateSnapshot): Observable<any> {
    return this.adminService.getBroadcastDetailById({broadcast_sent_user_id :route.params['id']})
    .pipe(catchError((err) => this.router.navigateByUrl('user/')));
  }
}
