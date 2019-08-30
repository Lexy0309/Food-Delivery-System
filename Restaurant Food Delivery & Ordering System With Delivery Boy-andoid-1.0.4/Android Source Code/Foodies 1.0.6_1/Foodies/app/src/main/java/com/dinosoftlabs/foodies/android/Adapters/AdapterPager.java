package com.dinosoftlabs.foodies.android.Adapters;

import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentStatePagerAdapter;
import android.util.SparseArray;
import android.view.ViewGroup;

import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.CartFragment;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.DealsFragment;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.OrdersFragment;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.RestaurantsFragment;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.UserAccountFragment;

/**
 * Created by Dell on 11/14/2016.
 */
public class AdapterPager extends FragmentStatePagerAdapter {
    SparseArray<Fragment> registeredFragments = new SparseArray<Fragment>();
    int mNumOfTabs;


        public AdapterPager(FragmentManager fragmentManager, int tabCount) {
        super(fragmentManager);
        this.mNumOfTabs=tabCount;
        }
        @Override
        public Fragment getItem(int position) {
        Fragment fm=null;

        switch (position) {

            case 0:
                fm = new RestaurantsFragment();
                break;
            case 1:
                 fm = new DealsFragment();
              break;
            case 2:
                fm = new OrdersFragment();
                break;

            case 3:
                fm = new CartFragment();
                break;
            case 4:
                fm = new UserAccountFragment();
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


