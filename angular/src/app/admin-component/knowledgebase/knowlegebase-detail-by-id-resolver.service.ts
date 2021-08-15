import { Injectable, } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, Router, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { UserService, AdminService } from '../../core';
import { catchError } from 'rxjs/operators';

@Injectable()
export class KnowledgebaseDetailByIdResolver implements Resolve<any> {
  constructor(
    private userService: UserService,
    private adminService: AdminService,
    private router: Router
  ) {}
  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> {
    return this.adminService.getKnowledgebaseDetailsById({knowledgebase_id:route.params['id']})
    .pipe(catchError((err) => this.router.navigateByUrl('/console/')));
  }
}
