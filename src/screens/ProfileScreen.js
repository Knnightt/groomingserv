import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Dimensions,
} from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { useSelector, useDispatch } from 'react-redux';
import { logout } from '../app/reducers/authReducer';
import { ROUTES } from '../utils';
import Sidebar from '../components/Sidebar';
import CustomButton from '../components/CustomButton';

const { width } = Dimensions.get('window');
const isTablet = width >= 768;

const ProfileScreen = () => {
  const navigation = useNavigation();
  const dispatch = useDispatch();
  const { user } = useSelector((state) => state.auth);

  const handleLogout = () => {
    dispatch(logout());
  };

  const handleNavigate = (route) => {
    if (route === 'Dashboard') {
      navigation.navigate(ROUTES.DASHBOARD);
    } else if (route === 'Appointments') {
      navigation.navigate(ROUTES.APPOINTMENTS);
    } else if (route === 'MyPets') {
      navigation.navigate(ROUTES.MY_PETS);
    } else if (route === 'Settings') {
      navigation.navigate(ROUTES.SETTINGS);
    }
  };

  const userName = user?.name || user?.username || 'User';
  const userEmail = user?.email || 'user@example.com';
  const firstName = userName.split(' ')[0];

  return (
    <View style={styles.container}>
      {isTablet && (
        <Sidebar
          activeRoute="Profile"
          onNavigate={handleNavigate}
          onLogout={handleLogout}
        />
      )}

      <View style={styles.mainContent}>
        <View style={styles.header}>
          <TouchableOpacity onPress={() => navigation.goBack()}>
            <Text style={styles.backButton}>‹ Back</Text>
          </TouchableOpacity>
          <Text style={styles.headerTitle}>Profile</Text>
          <View style={{ width: 50 }} />
        </View>

        <ScrollView style={styles.scrollContent} showsVerticalScrollIndicator={false}>
          {/* Profile Header */}
          <View style={styles.profileHeader}>
            <View style={styles.avatarLarge}>
              <Text style={styles.avatarLargeText}>
                {firstName.charAt(0).toUpperCase()}
              </Text>
            </View>
            <Text style={styles.profileName}>{userName}</Text>
            <Text style={styles.profileEmail}>{userEmail}</Text>
            <View style={styles.memberBadge}>
              <Text style={styles.memberBadgeText}>Premium Member</Text>
            </View>
          </View>

          {/* Profile Stats */}
          <View style={styles.statsRow}>
            <View style={styles.statItem}>
              <Text style={styles.statValue}>0</Text>
              <Text style={styles.statLabel}>Appointments</Text>
            </View>
            <View style={styles.statDivider} />
            <View style={styles.statItem}>
              <Text style={styles.statValue}>0</Text>
              <Text style={styles.statLabel}>Pets</Text>
            </View>
            <View style={styles.statDivider} />
            <View style={styles.statItem}>
              <Text style={styles.statValue}>0</Text>
              <Text style={styles.statLabel}>Points</Text>
            </View>
          </View>

          {/* Profile Options */}
          <View style={styles.optionsSection}>
            <TouchableOpacity style={styles.optionItem}>
              <Text style={styles.optionIcon}>👤</Text>
              <View style={styles.optionContent}>
                <Text style={styles.optionTitle}>Edit Profile</Text>
                <Text style={styles.optionSubtitle}>Update your personal information</Text>
              </View>
              <Text style={styles.optionArrow}>›</Text>
            </TouchableOpacity>

            <TouchableOpacity 
              style={styles.optionItem}
              onPress={() => navigation.navigate(ROUTES.MY_PETS)}
            >
              <Text style={styles.optionIcon}>🐾</Text>
              <View style={styles.optionContent}>
                <Text style={styles.optionTitle}>My Pets</Text>
                <Text style={styles.optionSubtitle}>Manage your furry companions</Text>
              </View>
              <Text style={styles.optionArrow}>›</Text>
            </TouchableOpacity>

            <TouchableOpacity 
              style={styles.optionItem}
              onPress={() => navigation.navigate(ROUTES.APPOINTMENTS)}
            >
              <Text style={styles.optionIcon}>📅</Text>
              <View style={styles.optionContent}>
                <Text style={styles.optionTitle}>Booking History</Text>
                <Text style={styles.optionSubtitle}>View past appointments</Text>
              </View>
              <Text style={styles.optionArrow}>›</Text>
            </TouchableOpacity>

            <TouchableOpacity style={styles.optionItem}>
              <Text style={styles.optionIcon}>💳</Text>
              <View style={styles.optionContent}>
                <Text style={styles.optionTitle}>Payment Methods</Text>
                <Text style={styles.optionSubtitle}>Manage your payment options</Text>
              </View>
              <Text style={styles.optionArrow}>›</Text>
            </TouchableOpacity>

            <TouchableOpacity 
              style={styles.optionItem}
              onPress={() => navigation.navigate(ROUTES.SETTINGS)}
            >
              <Text style={styles.optionIcon}>⚙️</Text>
              <View style={styles.optionContent}>
                <Text style={styles.optionTitle}>Settings</Text>
                <Text style={styles.optionSubtitle}>App preferences and notifications</Text>
              </View>
              <Text style={styles.optionArrow}>›</Text>
            </TouchableOpacity>
          </View>

          {/* Logout Button */}
          <CustomButton
            label="Log Out"
            onPress={handleLogout}
            variant="outline"
            buttonStyle={styles.logoutButton}
            textStyle={styles.logoutButtonText}
          />
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
  backButton: {
    fontSize: 18,
    color: '#6B8BB8',
    fontWeight: '500',
  },
  headerTitle: {
    fontSize: 20,
    fontWeight: '700',
    color: '#333333',
  },
  scrollContent: {
    flex: 1,
    padding: 24,
  },
  profileHeader: {
    alignItems: 'center',
    paddingVertical: 32,
    backgroundColor: '#FFFFFF',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#E8E8E8',
    marginBottom: 24,
  },
  avatarLarge: {
    width: 100,
    height: 100,
    borderRadius: 50,
    backgroundColor: '#6B8BB8',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 16,
  },
  avatarLargeText: {
    color: '#FFFFFF',
    fontSize: 40,
    fontWeight: '600',
  },
  profileName: {
    fontSize: 24,
    fontWeight: '700',
    color: '#333333',
    marginBottom: 4,
  },
  profileEmail: {
    fontSize: 14,
    color: '#666666',
    marginBottom: 16,
  },
  memberBadge: {
    backgroundColor: '#F0F4F8',
    paddingVertical: 6,
    paddingHorizontal: 16,
    borderRadius: 20,
  },
  memberBadgeText: {
    fontSize: 12,
    color: '#6B8BB8',
    fontWeight: '600',
  },
  statsRow: {
    flexDirection: 'row',
    backgroundColor: '#FFFFFF',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#E8E8E8',
    padding: 20,
    marginBottom: 24,
  },
  statItem: {
    flex: 1,
    alignItems: 'center',
  },
  statValue: {
    fontSize: 28,
    fontWeight: '700',
    color: '#6B8BB8',
    marginBottom: 4,
  },
  statLabel: {
    fontSize: 13,
    color: '#666666',
  },
  statDivider: {
    width: 1,
    backgroundColor: '#E8E8E8',
    marginVertical: 4,
  },
  optionsSection: {
    backgroundColor: '#FFFFFF',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#E8E8E8',
    overflow: 'hidden',
    marginBottom: 24,
  },
  optionItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 16,
    paddingHorizontal: 20,
    borderBottomWidth: 1,
    borderBottomColor: '#F0F0F0',
  },
  optionIcon: {
    fontSize: 24,
    marginRight: 16,
  },
  optionContent: {
    flex: 1,
  },
  optionTitle: {
    fontSize: 16,
    fontWeight: '500',
    color: '#333333',
    marginBottom: 2,
  },
  optionSubtitle: {
    fontSize: 13,
    color: '#999999',
  },
  optionArrow: {
    fontSize: 24,
    color: '#CCCCCC',
  },
  logoutButton: {
    borderColor: '#E74C3C',
    borderWidth: 2,
    marginBottom: 40,
  },
  logoutButtonText: {
    color: '#E74C3C',
  },
});

export default ProfileScreen;
