import { TestBed } from '@angular/core/testing';

import { MasterResolver } from './master.resolver';

describe('MasterResolver', () => {
  let resolver: MasterResolver;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    resolver = TestBed.inject(MasterResolver);
  });

  it('should be created', () => {
    expect(resolver).toBeTruthy();
  });
});
