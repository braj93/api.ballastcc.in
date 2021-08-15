import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DetailedTemplateComponent } from './detailed-template.component';

describe('DetailedTemplateComponent', () => {
  let component: DetailedTemplateComponent;
  let fixture: ComponentFixture<DetailedTemplateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DetailedTemplateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DetailedTemplateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
