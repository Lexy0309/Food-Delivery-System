package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;


import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.design.widget.TabLayout;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.dinosoftlabs.foodies.android.Adapters.AdapterPager;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.CustomViewPager;
import com.dinosoftlabs.foodies.android.Utils.SwipeDirection;


public class PagerMainActivity extends Fragment {
    //This is our tablayout
    public static TabLayout tabLayout;
    public static CustomViewPager viewPager;
    AdapterPager adapter;
    public int tabIconColor_Selected;
    public int tabIconColor_DeSelected;

    private int[] tabIcons1 = {R.drawable.ic_res_not_fil, R.drawable.ic_deals_not_filled,
            R.drawable.ic_order_not_fil, R.drawable.ic_cart_not_fil, R.drawable.ic_acc_not_fil};

    private int[] tabIcons = {R.drawable.ic_res_fill, R.drawable.ic_deals_filled,
            R.drawable.ic_order_fil, R.drawable.ic_cart_fil, R.drawable.ic_acc_fil};

    private int[] tabIconsWithBadge = {R.drawable.ic_res_not_fil, R.drawable.ic_deals_not_filled,
            R.drawable.ic_order_not_fil, R.drawable.ic_cart_empty_badge, R.drawable.ic_acc_not_fil};

    private int[] tabIconsFilledWithBadge = {R.drawable.ic_res_fill, R.drawable.ic_deals_filled,
            R.drawable.ic_order_fil, R.drawable.ic_cart_fill_badge, R.drawable.ic_acc_fil};

    // public static TextView tab_badge;

    boolean mIsReceiverRegistered = false;
    MyBroadcastReceiver mReceiver = null;

    SharedPreferences sPref;
    int count;


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        //    tabSelection();
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.activity_pager, container, false);

        sPref = getContext().getSharedPreferences(PreferenceClass.user,Context.MODE_PRIVATE);
        tabLayout = v.findViewById(R.id.tab_layout);

        viewPager = v.findViewById(R.id.pager);
        viewPager.setOffscreenPageLimit(5);

        tabLayout.addTab(tabLayout.newTab().setIcon(R.drawable.ic_res_fill));
        tabLayout.addTab(tabLayout.newTab().setIcon(R.drawable.ic_deals_not_filled));
        tabLayout.addTab(tabLayout.newTab().setIcon(R.drawable.ic_order_not_fil));
        tabLayout.addTab(tabLayout.newTab().setIcon(R.drawable.ic_cart_not_fil));
        tabLayout.addTab(tabLayout.newTab().setIcon(R.drawable.ic_acc_not_fil));

        if (tabLayout != null) {

            if (MainActivity.FLAG_MAIN) {

                for (int i = 1; i < tabIcons1.length; i++) {
                    tabLayout.getTabAt(0).setIcon(R.drawable.ic_restaurant_fill);
                    tabLayout.getTabAt(i).setIcon(tabIcons1[i]);

                    MainActivity.FLAG_MAIN = false;

                }
            } else {
                for (int i = 0; i < tabIcons1.length; i++) {
                    tabLayout.getTabAt(i).setIcon(tabIcons1[i]);
                }

            }

            for (int i = 0; i < tabLayout.getTabCount(); i++) {
                TabLayout.Tab tab = tabLayout.getTabAt(i);
                if (tab != null) tab.setCustomView(R.layout.tab_icon);

            }

            int getIfCarExist = sPref.getInt(PreferenceClass.CART_COUNT,0);
            count = sPref.getInt("count",0);

            if(count==0){
                TabLayout.Tab tab = tabLayout.getTabAt(3); // fourth tab
                View tabView = tab.getCustomView();
                TextView badgeText = (TextView) tabView.findViewById(R.id.tab_badge);
                badgeText.setVisibility(View.GONE);
                badgeText.setText(""+count);
            }
            else
            if(getIfCarExist==1){

                TabLayout.Tab tab = tabLayout.getTabAt(3); // fourth tab
                View tabView = tab.getCustomView();
                TextView badgeText = (TextView) tabView.findViewById(R.id.tab_badge);
                badgeText.setVisibility(View.VISIBLE);
                badgeText.setText(""+count);
            }


            adapter = new AdapterPager(getActivity().getSupportFragmentManager(), tabLayout.getTabCount());
            adapter.getRegisteredFragment(viewPager.getCurrentItem());

            viewPager.setAdapter(adapter);
            adapter.notifyDataSetChanged();
            viewPager.addOnPageChangeListener(new TabLayout.TabLayoutOnPageChangeListener(tabLayout));
            tabLayout.addOnTabSelectedListener(new TabLayout.ViewPagerOnTabSelectedListener(viewPager));
            viewPager.setAllowedSwipeDirection(SwipeDirection.none);

            //  setupTabIcons();
            tabLayout.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
                @Override
                public void onTabSelected(TabLayout.Tab tabSelected) {

                    tabSelected.setIcon(tabIcons[tabSelected.getPosition()]);

                    viewPager.setCurrentItem(tabSelected.getPosition());

                }

                @Override
                public void onTabUnselected(TabLayout.Tab tabSelected) {

                    tabSelected.setIcon(tabIcons1[tabSelected.getPosition()]);

                }

                @Override
                public void onTabReselected(TabLayout.Tab tabSelected) {

                }
            });
        }

        return v;

    }

    void selectPage(int pageIndex) {
        viewPager.setCurrentItem(pageIndex);

    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        if (mIsReceiverRegistered) {
            getActivity().unregisterReceiver(mReceiver);
            mReceiver = null;
            mIsReceiverRegistered = false;
        }
    }

    @Override
    public void onPause() {
        super.onPause();

    }

    @Override
    public void onResume() {
        super.onResume();
        if (!mIsReceiverRegistered) {
            if (mReceiver == null)
                mReceiver = new MyBroadcastReceiver();
            getActivity().registerReceiver(mReceiver, new IntentFilter("AddToCart"));
            mIsReceiverRegistered = true;
        }

        if (CartFragment.CART_LOGIN) {
            selectPage(3);
            CartFragment.CART_LOGIN = false;
            //    getActivity().getSupportFragmentManager().executePendingTransactions();
        }
        if (CartFragment.ORDER_PLACED) {
            selectPage(2);
            CartFragment.ORDER_PLACED = false;
        }

    }

    private class MyBroadcastReceiver extends BroadcastReceiver {

        @Override
        public void onReceive(Context context, Intent intent) {
            //  updateUI(intent);
            count = sPref.getInt("count",0);
            TabLayout.Tab tab = tabLayout.getTabAt(3); // fourth tab
            View tabView = tab.getCustomView();
            TextView badgeText = (TextView) tabView.findViewById(R.id.tab_badge);
            if(CartFragment.FLAG_CLEAR_ORDER){
                badgeText.setVisibility(View.GONE);
                CartFragment.FLAG_CLEAR_ORDER=false;
            }
            else {
                badgeText.setVisibility(View.VISIBLE);
                badgeText.setText(""+count);
            }

            if(count==0){
                badgeText.setVisibility(View.GONE);
                badgeText.setText(""+count);
            }

        }

    }

}

