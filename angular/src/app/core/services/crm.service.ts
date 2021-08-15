import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, BehaviorSubject, ReplaySubject } from 'rxjs';

import { ApiService } from './api.service';
import { JwtService } from './jwt.service';
import { User } from '../models';
import { map, distinctUntilChanged } from 'rxjs/operators';
import { Title, Meta } from '@angular/platform-browser';


@Injectable()
export class CrmService {
  public param_id: any = '';
  public user_type: any = '';
  constructor(
    private titleService: Title,
    private meta: Meta,
    private apiService: ApiService,
    private http: HttpClient,
    private jwtService: JwtService,
  ) { }

  // GET CrmContactIdList'S LIST
  getCrmContactByIdList(input: any): Observable<any> {
    return this.apiService.post('/admin/users_manage/users_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // ADD CONTACT
  addCrm(input: any): Observable<any> {
    return this.apiService.post('/crm/add', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // EDIT CONTACT
  editCrmContact(input: any): Observable<any> {
    return this.apiService.post('/crm/edit_crm_contact', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET DETAILS CONTACT
  getDetailsById(input: any): Observable<any> {
    return this.apiService.post('/crm/get_details_by_id', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET CRM LIST
  getList(input: any): Observable<any> {
    return this.apiService.post('/crm/list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET CRM FOR ADMIN LIST
  getCrmContacts(input: any): Observable<any> {
    return this.apiService.post('/crm/crm_contacts', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET Agency Billing FOR ADMIN LIST
  getAgencyBillingContacts(input: any): Observable<any> {
    return this.apiService.post('/admin/users_manage/get_agency_billing_contacts', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET CRM FOR ADMIN LIST
  getUserIndividual(input: any): Observable<any> {
    return this.apiService.post('/admin/users_manage/get_user_individual', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // DOWNLOAD SAMPLE FILE
  downloadSampleCRMFile(): Observable<any> {
    return this.apiService.get('/crm/download_sample_crm_file')
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // DOWNLOAD SAMPLE FILE
  exportCRMContactFile(input: any): Observable<any> {
    return this.apiService.get('/crm/export_crm_contact_file', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // ADD NOTES
  addNotes(input: any): Observable<any> {
    return this.apiService.post('/crm/add_notes', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET LOGS LIST
  getLogsList(input: any): Observable<any> {
    return this.apiService.post('/crm/crm_logs_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET NOTES LIST
  getNotesList(input: any): Observable<any> {
    return this.apiService.post('/crm/notes_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  getSourceList(input: any): Observable<any> {
    return this.apiService.post('/crm/get_source_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  autoSuggestions(input): Observable<any> {
    return this.apiService.post('/search/autosuggest', input )
       .pipe(map((data) => data.data));
  }

  addLogs(input: any): Observable<any> {
    return this.apiService.post('/crm/add_logs', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }
}
