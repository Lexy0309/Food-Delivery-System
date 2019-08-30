package com.dinosoftlabs.foodies.android.HActivitiesAndFragment.HAdapter;

import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentStatePagerAdapter;
import android.util.SparseArray;
import android.view.ViewGroup;

import com.dinosoftlabs.foodies.android.HActivitiesAndFragment.HJobsFragment;
import com.dinosoftlabs.foodies.android.HActivitiesAndFragment.HOrderHistory;
import com.dinosoftlabs.foodies.android.HActivitiesAndFragment.HProfileFragment;

/**
 * Created by Nabeel on 2/14/2018.
 */

public class HAdapter extends FragmentStatePagerAdapter {
    SparseArray<Fragment> registeredFragments = new SparseArray<Fragment>();
    int mNumOfTabs;


    public HAdapter(FragmentManager fragmentManager, int tabCount) {
        super(fragmentManager);
        this.mNumOfTabs = tabCount;
    }

    @Override
    public Fragment getItem(int position) {
        Fragment fm = null;
        switch (position) {
            case 0:
                fm = new HJobsFragment();
                break;
            case 1:
                fm = new HOrderHistory();
                break;
            case 2:
                fm = new HProfileFragment();
                break;
        }
        return fm;
    }

    @Override
    public int getCount() {
        return mNumOfTabs;
    }

    /* @Override
     public Parcelable saveState() {
     // Do Nothing
     return saveState();
     }*/
    @Override
    public int getItemPosition(Object object) {
        return POSITION_NONE;
    }


    @Override
    public Object instantiateItem(ViewGroup container, int position) {
        Fragment fragment = (Fragment) super.instantiateItem(container, position);
        registeredFragments.put(position, fragment);
        return fragment;
    }

    @Override
    public void destroyItem(ViewGroup container, int position, Object object) {
        registeredFragments.remove(position);
        super.destroyItem(container, position, object);
    }

    public Fragment getRegisteredFragment(int position) {
        return registeredFragments.get(position);
    }
}
