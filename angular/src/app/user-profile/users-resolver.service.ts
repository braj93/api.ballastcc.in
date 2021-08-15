import { Injectable, } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, Router, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { UserService } from '../core';
import { catchError } from 'rxjs/operators';

@Injectable()
export class UsersResolver implements Resolve<any> {
  constructor(
    private userService: UserService,
    private router: Router
  ) {}
  resolve(
    route: ActivatedRouteSnapshot, 
    state: RouterStateSnapshot): Observable<any> {
    return this.userService.usersList({
      keyword: "",
      pagination: {
          offset: 0,
          limit: 10
        },
      sort_by:{
        column_name: '',
        order_by: '',
      },
      filters: {  
        status: '',
        package: '',
      },
      last_login_at: {
        start: '',
        end: ''
      }
    })
    .pipe(catchError((err) => this.router.navigateByUrl('/console/')));
  }
}
