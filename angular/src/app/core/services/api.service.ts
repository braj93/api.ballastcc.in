import { Injectable } from '@angular/core';
import { environment } from '../../../environments/environment';
import { HttpHeaders, HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';

import { JwtService } from './jwt.service';
import { throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';

@Injectable()
export class ApiService {
  constructor(
    private http: HttpClient,
    private jwtService: JwtService
  ) {}

  private formatErrors(error: any) {
    return  throwError(error.error);
  }

  get(path: string, params: HttpParams = new HttpParams()): Observable<any> {
    return this.http.get(`${environment.api_url}${path}`, { params })
      .pipe(catchError(this.formatErrors));
  }

  put(path: string, body: Object = {}): Observable<any> {
    return this.http.put(
      `${environment.api_url}${path}`,
      JSON.stringify(body)
    ).pipe(catchError(this.formatErrors));
  }

  post(path: string, body: Object = {}): Observable<any> {
    return this.http.post(
      `${environment.api_url}${path}`,
      JSON.stringify(body)
    ).pipe(catchError(this.formatErrors));
  }

  postMultiPart(selectedFile: any, type:string): Observable<any> {
    const uploadData = new FormData();
    uploadData.append('qqfile', selectedFile, selectedFile.name);
    uploadData.append('type', type);
    let url = '/uploads/index';
    return this.http.post(
      `${environment.api_url}${url}`,
      uploadData
    ).pipe(catchError(this.formatErrors));
  }

  postBulkMultiPart(selectedFile: any, type:string, user_id:any): Observable<any> {
    const uploadData = new FormData();
    uploadData.append('qqfile', selectedFile, selectedFile.name);
    uploadData.append('type', type);
    uploadData.append('user_id', user_id);
    let url = '/uploads/bulk';
    return this.http.post(
      `${environment.api_url}${url}`,
      uploadData
    ).pipe(catchError(this.formatErrors));
  }

  postFileMultiPart(selectedFile: any, type:string): Observable<any> {
    const uploadData = new FormData();
    uploadData.append('qqfile', selectedFile, selectedFile.name);
    uploadData.append('type', type);
    let url = '/uploads/upload_search_audio';
    return this.http.post(
      `${environment.api_url}${url}`,
      uploadData
    ).pipe(catchError(this.formatErrors));
  }

  // postBase64Image(body: Object = {}): Observable<any> {
  //   let path = '/uploads/base64_upload_post';
  //   return this.http.post(
  //     `${environment.api_url}${path}`,
  //     JSON.stringify(body)
  //   ).pipe(catchError(this.formatErrors));
  // }

  delete(path): Observable<any> {
    return this.http.delete(
      `${environment.api_url}${path}`
    ).pipe(catchError(this.formatErrors));
  }
}
