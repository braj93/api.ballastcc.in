import { Injectable, } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, Router, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { UserService, AdminService } from '../../core';
import { catchError } from 'rxjs/operators';

@Injectable()
export class KnowledgebaseResolver implements Resolve<any> {
  constructor(
    private userService: UserService,
    private adminService: AdminService,
    private router: Router
  ) {}
  resolve(
    route: ActivatedRouteSnapshot, 
    state: RouterStateSnapshot): Observable<any> {
    return this.adminService.contentList({
      keyword: "",
      pagination: {
          offset: 0,
          limit: 10
        },
      sort_by:{
        column_name: '',
        order_by: '',
      },
      filter:""
    })
    .pipe(catchError((err) => this.router.navigateByUrl('/console/')));
  }
}
