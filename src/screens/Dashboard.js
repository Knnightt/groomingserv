import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  Dimensions,
  Image,
} from 'react-native';
import { useSelector, useDispatch } from 'react-redux';
import { useNavigation } from '@react-navigation/native';
import { ROUTES } from '../utils';
import { logout } from '../app/reducers/authReducer';
import Sidebar from '../components/Sidebar';
import StatCard from '../components/StatCard';
import CustomButton from '../components/CustomButton';

const { width } = Dimensions.get('window');
const isTablet = width >= 768;

const Dashboard = () => {
  const navigation = useNavigation();
  const dispatch = useDispatch();
  const { user } = useSelector((state) => state.auth);
  const [activeRoute, setActiveRoute] = useState('Dashboard');

  const handleLogout = () => {
    dispatch(logout());
  };

  const handleNavigate = (route) => {
    setActiveRoute(route);
    if (route === 'Profile') {
      navigation.navigate(ROUTES.PROFILE);
    } else if (route === 'Appointments') {
      navigation.navigate(ROUTES.APPOINTMENTS);
    } else if (route === 'MyPets') {
      navigation.navigate(ROUTES.MY_PETS);
    } else if (route === 'Settings') {
      navigation.navigate(ROUTES.SETTINGS);
    }
  };

  const handleBookAppointment = () => {
    navigation.navigate(ROUTES.APPOINTMENTS);
  };

  const handleCopyReferralLink = () => {
    // Placeholder for copy functionality
    console.log('Copy referral link');
  };

  const userName = user?.name || user?.username || 'User';
  const firstName = userName.split(' ')[0];

  // Mock data - in real app this would come from API
  const stats = {
    totalBookings: 0,
    myPets: 0,
    nextAppointment: null,
  };

  return (
    <View style={styles.container}>
      {/* Sidebar - visible on tablets, hidden on phones */}
      {isTablet && (
        <Sidebar
          activeRoute={activeRoute}
          onNavigate={handleNavigate}
          onLogout={handleLogout}
        />
      )}

      {/* Main Content */}
      <View style={styles.mainContent}>
        {/* Top Header */}
        <View style={styles.header}>
          <Text style={styles.headerTitle}>Dashboard</Text>
          <View style={styles.headerRight}>
            <View style={styles.userBadge}>
              <View style={styles.pawIcon}>
                <Text style={styles.pawEmoji}>🐾</Text>
              </View>
            </View>
            <View style={styles.userInfo}>
              <Text style={styles.userName}>{userName}</Text>
              <Text style={styles.userRole}>Premium Member</Text>
            </View>
            <TouchableOpacity
              style={styles.avatarContainer}
              onPress={() => handleNavigate('Profile')}
            >
              <View style={styles.avatar}>
                <Text style={styles.avatarText}>
                  {firstName.charAt(0).toUpperCase()}
                </Text>
              </View>
            </TouchableOpacity>
          </View>
        </View>

        <ScrollView
          style={styles.scrollContent}
          showsVerticalScrollIndicator={false}
        >
          {/* Greeting Section */}
          <View style={styles.greetingSection}>
            <View style={styles.greetingLeft}>
              <Text style={styles.greetingTitle}>Good Day {firstName}</Text>
              <Text style={styles.greetingSubtitle}>
                Here's what's happening with your pets today.
              </Text>
            </View>
            <CustomButton
              label="Book Appointment"
              onPress={handleBookAppointment}
              buttonStyle={styles.bookButton}
              textStyle={styles.bookButtonText}
            />
          </View>

          {/* Stats Cards Row */}
          <View style={styles.statsRow}>
            <StatCard
              title="TOTAL BOOKINGS"
              value={stats.totalBookings}
              subtitle="Lifetime appointments"
              style={styles.statCard}
            />
            <StatCard
              title="MY PETS"
              value={stats.myPets}
              subtitle="Furry companions"
              style={styles.statCard}
            />
            <StatCard
              title="NEXT APPOINTMENT"
              value={stats.nextAppointment ? 'Scheduled' : 'No upcoming appointments'}
              isHighlighted
              style={[styles.statCard, styles.highlightedCard]}
            />
          </View>

          {/* Bottom Section */}
          <View style={styles.bottomSection}>
            {/* Upcoming Schedule */}
            <View style={styles.scheduleSection}>
              <View style={styles.scheduleTitleRow}>
                <Text style={styles.scheduleTitle}>Upcoming Schedule</Text>
                <TouchableOpacity>
                  <Text style={styles.viewAllLink}>View All</Text>
                </TouchableOpacity>
              </View>
              <View style={styles.scheduleCard}>
                <View style={styles.emptySchedule}>
                  <Text style={styles.emptyScheduleText}>
                    No upcoming appointments
                  </Text>
                  <CustomButton
                    label="Book Now"
                    onPress={handleBookAppointment}
                    buttonStyle={styles.bookNowButton}
                    textStyle={styles.bookNowButtonText}
                  />
                </View>
              </View>
            </View>

            {/* Right Side - My Pets & Referral */}
            <View style={styles.rightSection}>
              {/* My Pets */}
              <View style={styles.myPetsSection}>
                <Text style={styles.myPetsTitle}>MY PETS</Text>
                <TouchableOpacity
                  style={styles.addPetButton}
                  onPress={() => handleNavigate('MyPets')}
                >
                  <Text style={styles.addPetButtonText}>Add New Pet</Text>
                </TouchableOpacity>
              </View>

              {/* Referral Card */}
              <View style={styles.referralCard}>
                <View style={styles.referralIconContainer}>
                  <View style={styles.referralIcon}>
                    <Text style={styles.referralIconText}>🔗</Text>
                  </View>
                </View>
                <Text style={styles.referralTitle}>Refer a Friend</Text>
                <Text style={styles.referralSubtitle}>
                  Earn 60 points for every friend you refer to us!
                </Text>
                <TouchableOpacity onPress={handleCopyReferralLink}>
                  <Text style={styles.copyLinkText}>Copy Link</Text>
                </TouchableOpacity>
              </View>
            </View>
          </View>
        </ScrollView>

        {/* Bottom Navigation for Mobile */}
        {!isTablet && (
          <View style={styles.bottomNav}>
            <TouchableOpacity
              style={styles.bottomNavItem}
              onPress={() => setActiveRoute('Dashboard')}
            >
              <Text
                style={[
                  styles.bottomNavIcon,
                  activeRoute === 'Dashboard' && styles.bottomNavActive,
                ]}
              >
                🏠
              </Text>
              <Text
                style={[
                  styles.bottomNavLabel,
                  activeRoute === 'Dashboard' && styles.bottomNavActiveLabel,
                ]}
              >
                Home
              </Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={styles.bottomNavItem}
              onPress={() => handleNavigate('Appointments')}
            >
              <Text style={styles.bottomNavIcon}>📅</Text>
              <Text style={styles.bottomNavLabel}>Appointments</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={styles.bottomNavItem}
              onPress={() => handleNavigate('MyPets')}
            >
              <Text style={styles.bottomNavIcon}>🐾</Text>
              <Text style={styles.bottomNavLabel}>My Pets</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={styles.bottomNavItem}
              onPress={() => handleNavigate('Profile')}
            >
              <Text style={styles.bottomNavIcon}>👤</Text>
              <Text style={styles.bottomNavLabel}>Profile</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={styles.bottomNavItem}
              onPress={handleLogout}
            >
              <Text style={styles.bottomNavIcon}>🚪</Text>
              <Text style={styles.bottomNavLabel}>Logout</Text>
            </TouchableOpacity>
          </View>
        )}
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
  headerRight: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  userBadge: {
    marginRight: 12,
  },
  pawIcon: {
    width: 36,
    height: 36,
    borderRadius: 18,
    backgroundColor: '#F0F4F8',
    justifyContent: 'center',
    alignItems: 'center',
  },
  pawEmoji: {
    fontSize: 18,
  },
  userInfo: {
    marginRight: 12,
    alignItems: 'flex-end',
  },
  userName: {
    fontSize: 14,
    fontWeight: '600',
    color: '#333333',
  },
  userRole: {
    fontSize: 12,
    color: '#6B8BB8',
  },
  avatarContainer: {
    marginLeft: 8,
  },
  avatar: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: '#4A5568',
    justifyContent: 'center',
    alignItems: 'center',
  },
  avatarText: {
    color: '#FFFFFF',
    fontSize: 16,
    fontWeight: '600',
  },
  scrollContent: {
    flex: 1,
    padding: 24,
  },
  greetingSection: {
    flexDirection: isTablet ? 'row' : 'column',
    justifyContent: 'space-between',
    alignItems: isTablet ? 'center' : 'flex-start',
    marginBottom: 24,
  },
  greetingLeft: {
    flex: 1,
    marginBottom: isTablet ? 0 : 16,
  },
  greetingTitle: {
    fontSize: 28,
    fontWeight: '700',
    color: '#333333',
    marginBottom: 4,
  },
  greetingSubtitle: {
    fontSize: 14,
    color: '#6B8BB8',
  },
  bookButton: {
    backgroundColor: '#6B8BB8',
    paddingVertical: 14,
    paddingHorizontal: 24,
    borderRadius: 25,
  },
  bookButtonText: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '600',
  },
  statsRow: {
    flexDirection: isTablet ? 'row' : 'column',
    gap: 16,
    marginBottom: 24,
  },
  statCard: {
    flex: isTablet ? 1 : undefined,
    marginBottom: isTablet ? 0 : 12,
  },
  highlightedCard: {
    backgroundColor: '#6B8BB8',
  },
  bottomSection: {
    flexDirection: isTablet ? 'row' : 'column',
    gap: 24,
  },
  scheduleSection: {
    flex: isTablet ? 2 : undefined,
  },
  scheduleTitleRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 16,
  },
  scheduleTitle: {
    fontSize: 18,
    fontWeight: '600',
    color: '#333333',
  },
  viewAllLink: {
    fontSize: 14,
    color: '#6B8BB8',
  },
  scheduleCard: {
    backgroundColor: '#FFFFFF',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#E8E8E8',
    padding: 40,
    minHeight: 180,
  },
  emptySchedule: {
    alignItems: 'center',
    justifyContent: 'center',
  },
  emptyScheduleText: {
    fontSize: 16,
    color: '#6B8BB8',
    marginBottom: 20,
  },
  bookNowButton: {
    backgroundColor: '#6B8BB8',
    paddingVertical: 14,
    paddingHorizontal: 32,
    borderRadius: 25,
  },
  bookNowButtonText: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '600',
  },
  rightSection: {
    flex: isTablet ? 1 : undefined,
    gap: 16,
  },
  myPetsSection: {
    marginBottom: 16,
  },
  myPetsTitle: {
    fontSize: 14,
    fontWeight: '600',
    color: '#333333',
    marginBottom: 12,
  },
  addPetButton: {
    backgroundColor: '#FFFFFF',
    borderRadius: 25,
    borderWidth: 1,
    borderColor: '#6B8BB8',
    paddingVertical: 14,
    paddingHorizontal: 24,
    alignItems: 'center',
  },
  addPetButtonText: {
    fontSize: 14,
    color: '#6B8BB8',
    fontWeight: '500',
  },
  referralCard: {
    backgroundColor: '#FFFFFF',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#E8E8E8',
    padding: 24,
    alignItems: 'center',
  },
  referralIconContainer: {
    marginBottom: 16,
  },
  referralIcon: {
    width: 48,
    height: 48,
    borderRadius: 24,
    backgroundColor: '#F0F4F8',
    justifyContent: 'center',
    alignItems: 'center',
  },
  referralIconText: {
    fontSize: 24,
  },
  referralTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#333333',
    marginBottom: 8,
  },
  referralSubtitle: {
    fontSize: 13,
    color: '#666666',
    textAlign: 'center',
    marginBottom: 16,
  },
  copyLinkText: {
    fontSize: 14,
    color: '#6B8BB8',
    fontWeight: '500',
  },
  bottomNav: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    alignItems: 'center',
    backgroundColor: '#FFFFFF',
    paddingVertical: 12,
    borderTopWidth: 1,
    borderTopColor: '#E8E8E8',
  },
  bottomNavItem: {
    alignItems: 'center',
  },
  bottomNavIcon: {
    fontSize: 20,
    marginBottom: 4,
  },
  bottomNavLabel: {
    fontSize: 11,
    color: '#666666',
  },
  bottomNavActive: {
    opacity: 1,
  },
  bottomNavActiveLabel: {
    color: '#6B8BB8',
    fontWeight: '600',
  },
});

export default Dashboard;
