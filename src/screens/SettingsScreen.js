import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Switch,
  Dimensions,
} from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { useDispatch } from 'react-redux';
import { logout } from '../app/reducers/authReducer';
import { ROUTES } from '../utils';
import Sidebar from '../components/Sidebar';

const { width } = Dimensions.get('window');
const isTablet = width >= 768;

const SettingsScreen = () => {
  const navigation = useNavigation();
  const dispatch = useDispatch();

  const [notifications, setNotifications] = useState(true);
  const [emailUpdates, setEmailUpdates] = useState(true);
  const [smsReminders, setSmsReminders] = useState(false);

  const handleLogout = () => {
    dispatch(logout());
  };

  const handleNavigate = (route) => {
    if (route === 'Dashboard') {
      navigation.navigate(ROUTES.DASHBOARD);
    } else if (route === 'Profile') {
      navigation.navigate(ROUTES.PROFILE);
    } else if (route === 'Appointments') {
      navigation.navigate(ROUTES.APPOINTMENTS);
    } else if (route === 'MyPets') {
      navigation.navigate(ROUTES.MY_PETS);
    }
  };

  const SettingItem = ({ title, subtitle, hasSwitch, value, onValueChange, onPress }) => (
    <TouchableOpacity
      style={styles.settingItem}
      onPress={onPress}
      disabled={hasSwitch}
    >
      <View style={styles.settingItemLeft}>
        <Text style={styles.settingItemTitle}>{title}</Text>
        {subtitle && <Text style={styles.settingItemSubtitle}>{subtitle}</Text>}
      </View>
      {hasSwitch ? (
        <Switch
          value={value}
          onValueChange={onValueChange}
          trackColor={{ false: '#E8E8E8', true: '#6B8BB8' }}
          thumbColor="#FFFFFF"
        />
      ) : (
        <Text style={styles.settingItemArrow}>›</Text>
      )}
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      {isTablet && (
        <Sidebar
          activeRoute="Settings"
          onNavigate={handleNavigate}
          onLogout={handleLogout}
        />
      )}

      <View style={styles.mainContent}>
        <View style={styles.header}>
          <Text style={styles.headerTitle}>Settings</Text>
        </View>

        <ScrollView style={styles.scrollContent} showsVerticalScrollIndicator={false}>
          <View style={styles.titleSection}>
            <Text style={styles.pageTitle}>App Settings</Text>
            <Text style={styles.pageSubtitle}>
              Customize your app experience and preferences
            </Text>
          </View>

          {/* Notifications Section */}
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Notifications</Text>
            <View style={styles.sectionContent}>
              <SettingItem
                title="Push Notifications"
                subtitle="Receive appointment reminders"
                hasSwitch
                value={notifications}
                onValueChange={setNotifications}
              />
              <SettingItem
                title="Email Updates"
                subtitle="Get updates on promotions and news"
                hasSwitch
                value={emailUpdates}
                onValueChange={setEmailUpdates}
              />
              <SettingItem
                title="SMS Reminders"
                subtitle="Receive text message reminders"
                hasSwitch
                value={smsReminders}
                onValueChange={setSmsReminders}
              />
            </View>
          </View>

          {/* Account Section */}
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Account</Text>
            <View style={styles.sectionContent}>
              <SettingItem
                title="Change Password"
                onPress={() => {}}
              />
              <SettingItem
                title="Update Email"
                onPress={() => {}}
              />
              <SettingItem
                title="Linked Accounts"
                subtitle="Google, Facebook"
                onPress={() => {}}
              />
            </View>
          </View>

          {/* Privacy Section */}
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Privacy & Security</Text>
            <View style={styles.sectionContent}>
              <SettingItem
                title="Privacy Policy"
                onPress={() => {}}
              />
              <SettingItem
                title="Terms of Service"
                onPress={() => {}}
              />
              <SettingItem
                title="Data Management"
                subtitle="Download or delete your data"
                onPress={() => {}}
              />
            </View>
          </View>

          {/* Support Section */}
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Support</Text>
            <View style={styles.sectionContent}>
              <SettingItem
                title="Help Center"
                onPress={() => {}}
              />
              <SettingItem
                title="Contact Us"
                onPress={() => {}}
              />
              <SettingItem
                title="Report a Problem"
                onPress={() => {}}
              />
            </View>
          </View>

          {/* App Info */}
          <View style={styles.appInfo}>
            <Text style={styles.appInfoText}>Quibot's Grooming Services</Text>
            <Text style={styles.appVersionText}>Version 1.0.0</Text>
          </View>

          {/* Logout Button */}
          <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
            <Text style={styles.logoutButtonText}>Log Out</Text>
          </TouchableOpacity>
        </ScrollView>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    flexDirection: 'row',
    backgroundColor: '#F8F9FA',
  },
  mainContent: {
    flex: 1,
    backgroundColor: '#F8F9FA',
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 24,
    paddingVertical: 16,
    backgroundColor: '#FFFFFF',
    borderBottomWidth: 1,
    borderBottomColor: '#E8E8E8',
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: '700',
    color: '#333333',
  },
  scrollContent: {
    flex: 1,
    padding: 24,
  },
  titleSection: {
    marginBottom: 24,
  },
  pageTitle: {
    fontSize: 28,
    fontWeight: '700',
    color: '#333333',
    marginBottom: 8,
  },
  pageSubtitle: {
    fontSize: 14,
    color: '#6B8BB8',
  },
  section: {
    marginBottom: 24,
  },
  sectionTitle: {
    fontSize: 14,
    fontWeight: '600',
    color: '#666666',
    textTransform: 'uppercase',
    marginBottom: 12,
  },
  sectionContent: {
    backgroundColor: '#FFFFFF',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#E8E8E8',
    overflow: 'hidden',
  },
  settingItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: 16,
    paddingHorizontal: 20,
    borderBottomWidth: 1,
    borderBottomColor: '#F0F0F0',
  },
  settingItemLeft: {
    flex: 1,
  },
  settingItemTitle: {
    fontSize: 16,
    color: '#333333',
    fontWeight: '500',
  },
  settingItemSubtitle: {
    fontSize: 13,
    color: '#999999',
    marginTop: 2,
  },
  settingItemArrow: {
    fontSize: 24,
    color: '#CCCCCC',
  },
  appInfo: {
    alignItems: 'center',
    marginVertical: 24,
  },
  appInfoText: {
    fontSize: 14,
    color: '#666666',
    fontWeight: '500',
  },
  appVersionText: {
    fontSize: 12,
    color: '#999999',
    marginTop: 4,
  },
  logoutButton: {
    backgroundColor: '#FFFFFF',
    borderRadius: 25,
    borderWidth: 2,
    borderColor: '#E74C3C',
    paddingVertical: 14,
    alignItems: 'center',
    marginBottom: 40,
  },
  logoutButtonText: {
    fontSize: 16,
    color: '#E74C3C',
    fontWeight: '600',
  },
});

export default SettingsScreen;
